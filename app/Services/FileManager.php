<?php

namespace App\Services;

use Aws\S3\S3Client;
use Exception;
use InvalidArgumentException;
use Ions\Bundles\Path;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\PathTraversalDetected;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    // Constants for configuration
    private const MAX_FILE_SIZE = 100 * 1024 * 1024; // 100MB
    private const MAX_RETRIES = 3;
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf',
        'text/plain',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'video/mp4',
        'video/avi',
        'video/mpeg',
        'video/mov',
    ];

    private Filesystem $filesystem;
    private string $disk;
    private ?string $basePath;
    private ?string $bucket;
    private ?LoggerInterface $logger;
    private array $config;

    /**
     * @throws Exception
     */
    public function __construct(
        ?string $disk = null,
        ?array $config = null,
        ?LoggerInterface $logger = null,
    ) {
        $this->logger = $logger ?? $this->createDefaultLogger();
        $this->disk = $disk ?? config('filesystem.disks.default', 'local');
        $this->config = $config ?? $this->getDefaultConfig();
        $this->validateConfig();

        $this->basePath = config('filesystem.disks.local.root');
        $this->bucket = config('filesystem.disks.s3.bucket');
        $this->filesystem = $this->initializeFilesystem();
    }

    private function getDefaultConfig(): array
    {
        return [
            'max_file_size' => self::MAX_FILE_SIZE,
            'allowed_mime_types' => self::ALLOWED_MIME_TYPES,
            'max_retries' => self::MAX_RETRIES,
        ];
    }

    private function createDefaultLogger(): LoggerInterface
    {
        $logger = new Logger('FileManager');
        $logger->pushHandler(new StreamHandler(Path::logs('app.log'), Logger::DEBUG));
        return $logger;
    }

    // Helper method for logging to handle null logger gracefully
    private function log(string $level, string $message, array $context = []): void
    {
        if ($this->logger) {
            match ($level) {
                'error' => $this->logger->error($message, $context),
                'info' => $this->logger->info($message, $context),
                'warning' => $this->logger->warning($message, $context),
                'debug' => $this->logger->debug($message, $context),
                default => $this->logger->info($message, $context),
            };
        }
    }



    private function validateConfig(): void
    {
        if (!isset($this->config['max_file_size']) || !is_int($this->config['max_file_size'])) {
            throw new InvalidArgumentException('Invalid max_file_size configuration');
        }
        if (!isset($this->config['allowed_mime_types']) || !is_array($this->config['allowed_mime_types'])) {
            throw new InvalidArgumentException('Invalid allowed_mime_types configuration');
        }
    }


    /**
     * @throws Exception
     */
    private function initializeFilesystem(): Filesystem
    {
        return match ($this->disk) {
            'local' => new Filesystem(
                new LocalFilesystemAdapter($this->basePath),
            ),
            's3' => new Filesystem(
                new AwsS3V3Adapter(
                    $this->createS3Client(),
                    $this->bucket,
                    $this->basePath,
                ),
            ),
            default => throw new InvalidArgumentException("Unsupported disk type: $this->disk")
        };
    }

    /**
     * @throws Exception
     */
    private function createS3Client(): S3Client
    {
        try {
            return new S3Client([
                'region' => config('filesystem.disks.s3.region'),
                'version' => config('filesystem.disks.s3.version', 'latest'),
                'credentials' => [
                    'key' => config('filesystem.disks.s3.key'),
                    'secret' => config('filesystem.disks.s3.secret'),
                ],
            ]);
        } catch (Exception $e) {
            $this->log('error','Failed to create S3 client', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Upload a file from various sources with a validation and retry mechanism
     * @throws FilesystemException
     */
    public function upload(mixed $file, string $path, bool $preserveFilename = false): array
    {
        $retries = 0;
        $lastException = null;

        try {
            // Validate file before processing
            $this->validateFile($file);

            $content = $this->getFileContent($file);
            $filename = $this->generateFilename($file, $preserveFilename);
            $fullPath = $this->normalizePath($path . '/' . $filename);
            $mimeType = $this->detectMimeType($file);

            while ($retries < $this->config['max_retries']) {
                try {
                    $this->filesystem->write($fullPath, $content);

                    $this->log('info','File uploaded successfully', [
                        'path' => $fullPath,
                        'size' => strlen($content),
                        'mime_type' => $mimeType
                    ]);

                    return [
                        'success' => true,
                        'path' => $fullPath,
                        'filename' => $filename,
                        'size' => strlen($content),
                        'mime_type' => $mimeType,
                        'metadata' => $this->getMetadata($fullPath)
                    ];
                } catch (UnableToWriteFile $e) {
                    $lastException = $e;
                    $retries++;
                    if ($retries < $this->config['max_retries']) {
                        sleep(2 ** $retries); // Exponential backoff
                    }
                }
            }

            throw $lastException ?? new Exception('Upload failed after retries');
        } catch (Exception $e) {
            $this->log('error','File upload failed', [
                'error' => $e->getMessage(),
                'path' => $path ?? 'unknown'
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $this->getErrorCode($e)
            ];
        }
    }

    /**
     * Validate file before processing
     * @throws InvalidArgumentException
     */
    private function validateFile($file): void
    {
        if ($file instanceof UploadedFile) {
            if ($file->getSize() > $this->config['max_file_size']) {
                throw new InvalidArgumentException('File size exceeds maximum allowed size');
            }

            if (!in_array($file->getMimeType(), $this->config['allowed_mime_types'], true)) {
                throw new InvalidArgumentException('File type not allowed');
            }
        }
    }

    /**
     * Normalize a path to prevent directory traversal
     */
    private function normalizePath(string $path): string
    {
        $path = trim($path, '/');

        // Prevent directory traversal
        if (str_contains($path, '..')) {
            throw new PathTraversalDetected("Path contains invalid sequences");
        }

        return $path;
    }

    /**
     * Detect MIME type of the file
     */
    private function detectMimeType($file): ?string
    {
        if ($file instanceof UploadedFile) {
            return $file->getMimeType();
        }

        if (is_string($file) && is_file($file)) {
            return mime_content_type($file);
        }

        return null;
    }



    /**
     * Handle file upload or update
     *
     * @param UploadedFile|null $newFile The new file to upload
     * @param string|null $existingFile The existing file path to replace
     * @param string $directory The directory to store the file
     * @param bool $preserveFilename Whether to keep the original filename
     * @return array
     * @throws FilesystemException
     */
    public function uploadOrUpdate(
        ?UploadedFile $newFile,
        ?string $existingFile,
        string $directory,
        bool $preserveFilename = false
    ): array {
        // If no new file is uploaded, return the existing filename
        if (!$newFile) {
            return [
                'success' => true,
                'filename' => $existingFile,
            ];
        }

        // Ensure the directory exists
        if (!$this->ensureDirectoryExists($directory)) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Failed to create directory',
            ];
        }

        // Upload the new file
        $uploadResult = $this->upload($newFile, $directory, $preserveFilename);

        // If upload was successful and there's an existing file, delete it
        if ($uploadResult['success'] && $existingFile) {
            $this->delete($directory . '/' . $existingFile);
        }

        return [
            'success' => $uploadResult['success'],
            'filename' => $uploadResult['success'] ? $uploadResult['filename'] : null,
            'error' => $uploadResult['success'] ? null : ($uploadResult['error'] ?? 'Upload failed'),
        ];
    }


    /**
     * Check if a file or directory exists
     * @throws FilesystemException
     */
    public function exists(string $path): bool
    {
        return $this->filesystem->has(trim($path, '/'));
    }

    /**
     * Create a directory
     */
    public function createDirectory(string $path): bool
    {
        try {
            $this->filesystem->createDirectory(trim($path, '/'));
            return true;
        } catch (FilesystemException) {
            return false;
        }
    }

    /**
     * Safe delete operation with logging
     */
    public function safeDelete(string $path): bool
    {
        try {
            $normalizedPath = $this->normalizePath($path);

            if (!$this->exists($normalizedPath)) {
                $this->log('info','File not found for deletion', ['path' => $normalizedPath]);
                return false;
            }

            $metadata = $this->getMetadata($normalizedPath);
            $this->filesystem->delete($normalizedPath);

            $this->log('info','File deleted successfully', [
                'path' => $normalizedPath,
                'metadata' => $metadata
            ]);

            return true;
        } catch (FilesystemException $e) {
            $this->log('error','File deletion failed', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get standardized error code for exceptions
     */
    private function getErrorCode(Exception $e): string
    {
        return match (true) {
            $e instanceof UnableToWriteFile => 'WRITE_ERROR',
            $e instanceof PathTraversalDetected => 'INVALID_PATH',
            $e instanceof InvalidArgumentException => 'VALIDATION_ERROR',
            default => 'UNKNOWN_ERROR'
        };
    }



    /**
     * Delete a file
     */
    public function delete(string $path): bool
    {
        try {
            $this->filesystem->delete(trim($path, '/'));
            return true;
        } catch (FilesystemException) {
            return false;
        }
    }

    /**
     * Delete a directory and its contents
     */
    public function deleteDirectory(string $path): bool
    {
        try {
            $this->filesystem->deleteDirectory(trim($path, '/'));
            return true;
        } catch (FilesystemException) {
            return false;
        }
    }

    /**
     * Copy a file
     */
    public function copy(string $source, string $destination): bool
    {
        try {
            $this->filesystem->copy(
                trim($source, '/'),
                trim($destination, '/'),
            );
            return true;
        } catch (FilesystemException) {
            return false;
        }
    }

    /**
     * Move/rename a file
     */
    public function move(string $source, string $destination): bool
    {
        try {
            $this->filesystem->move(
                trim($source, '/'),
                trim($destination, '/'),
            );
            return true;
        } catch (FilesystemException) {
            return false;
        }
    }

    /**
     * Get file contents
     * @throws FilesystemException
     */
    public function get(string $path): string|false
    {
        try {
            return $this->filesystem->read(trim($path, '/'));
        } catch (UnableToReadFile) {
            return false;
        }
    }

    /**
     * Get file metadata
     */
    public function getMetadata(string $path): array
    {
        try {
            return [
                'mimetype' => $this->filesystem->mimeType(trim($path, '/')),
                'size' => $this->filesystem->fileSize(trim($path, '/')),
                'lastModified' => $this->filesystem->lastModified(trim($path, '/')),
            ];
        } catch (FilesystemException) {
            return [];
        }
    }

    /**
     * List contents of a directory
     */
    public function listContents(string $path = '', bool $recursive = false): array
    {
        try {
            $contents = [];
            foreach ($this->filesystem->listContents(trim($path, '/'), $recursive) as $item) {
                $contents[] = [
                    'path' => $item->path(),
                    'type' => $item->isFile() ? 'file' : 'dir',
                    'size' => $item->isFile() ? $item->fileSize() : null,
                    'lastModified' => $item->lastModified(),
                ];
            }
            return $contents;
        } catch (FilesystemException) {
            return [];
        }
    }

    /**
     * Get a temporary URL for a file (S3 only)
     * @throws Exception
     */
    public function getTemporaryUrl(string $path, int $expiration = 3600): ?string
    {
        if ($this->disk !== 's3') {
            return null;
        }

        $s3Client = $this->createS3Client();
        $command = $s3Client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => trim($path, '/'),
        ]);

        return (string)$s3Client->createPresignedRequest($command, "+$expiration seconds")->getUri();
    }

    private function getFileContent(mixed $file): string
    {
        if ($file instanceof UploadedFile) {
            return file_get_contents($file->getRealPath());
        }

        if (is_string($file) && is_file($file)) {
            return file_get_contents($file);
        }

        if (is_string($file)) {
            return $file;
        }

        throw new InvalidArgumentException('Invalid file type provided');
    }

    private function generateFilename(mixed $file, bool $preserveFilename): string
    {
        if ($file instanceof UploadedFile) {
            return $preserveFilename
                ? $file->getClientOriginalName()
                : uniqid('', true) . '.' . $file->getClientOriginalExtension();
        }

        if (is_string($file) && is_file($file)) {
            return $preserveFilename
                ? basename($file)
                : uniqid('', true) . '.' . pathinfo($file, PATHINFO_EXTENSION);
        }

        return uniqid('', true);
    }

    /**
     * @throws Exception
     */
    public function changeDisk(string $disk): self
    {
        $this->disk = $disk;
        $this->filesystem = $this->initializeFilesystem();
        return $this;
    }

    /**
     * Ensures a directory exists, creates it if it doesn't
     */
    public function ensureDirectoryExists(string $path): bool
    {
        $path = trim($path, '/');

        try {
            // For S3, we need to ensure the path ends with a forward slash
            if ($this->disk === 's3') {
                $path .= '/';

                // For S3, we can create an empty object to represent the directory
                try {
                    if (!$this->exists($path)) {
                        $this->filesystem->write($path . '.gitkeep', '');
                    }
                    return true;
                } catch (FilesystemException $e) {
                    // Log the specific S3 error for debugging
                    $this->log('error','S3 Directory Creation Error', [
                        'path' => $path,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return false;
                }
            }

            // For local storage
            if (!$this->exists($path)) {
                return $this->createDirectory($path);
            }

            return true;
        } catch (FilesystemException $e) {
            $this->log('error','Directory Creation Error', [
                'path' => $path,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

}
