<?php

namespace App\Services;

use App\Exceptions\DecryptionException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use JsonException;
use Throwable;

class TokenService
{
    private static string $algorithm = 'HS256';

    /**
     * @throws JsonException
     */
    public static function generateAccessToken(array $userData, $issuedTime = 3600 * 24 * 30): string
    {
        $secretKey = env('JWT_SECRET');
        $issuedAt = time();
        $expirationTime = $issuedAt + $issuedTime; // jwt valid for 30 days from the issued time
        // encrypt the data before sending it
        $encryptionKey = env('ENCRYPTION_KEY');
        $isCryptoStrong = false;
        $iv = openssl_random_pseudo_bytes(16, $isCryptoStrong); // Generate a secure IV
        if (!$isCryptoStrong) {
            throw new DecryptionException('IV generation failed: not cryptographically strong', 500);
        }
        $encryptedData = openssl_encrypt(
            json_encode($userData, JSON_THROW_ON_ERROR),
            'AES-256-CBC',
            $encryptionKey,
            0,
            $iv,
        );
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $encryptedData,
            'iv' => base64_encode($iv),
        ];

        return JWT::encode($payload, $secretKey, self::$algorithm);
    }

    public static function verifyAccessToken(?string $token): array
    {
        try {
            if (!$token) {
                throw new DecryptionException('Token not provided', 404);
            }
            $secretKey = env('JWT_SECRET');
            $encryptionKey = env('ENCRYPTION_KEY');
            $decoded = JWT::decode($token, new Key($secretKey, self::$algorithm));
            $encryptedData = $decoded->data;
            $iv = base64_decode($decoded->iv); // Retrieve the IV from the payload
            // Decrypt the data
            $decryptedData = openssl_decrypt(
                $encryptedData,
                'AES-256-CBC',
                $encryptionKey,
                0,
                $iv,
            );
            if ($decryptedData === false) {
                throw new DecryptionException('Failed to decrypt data', 500);
            }
            return json_decode($decryptedData, true, 512, JSON_THROW_ON_ERROR);
        } catch (ExpiredException $e) {
            throw new DecryptionException('Token has expired', 401);
        } catch (SignatureInvalidException) {
            throw new DecryptionException('Invalid token signature', 500);
        } catch (DecryptionException) {
            throw new DecryptionException('Invalid token', 500);
        } catch (JsonException) {
            throw new DecryptionException('Invalid token data', 500);
        } catch (Throwable) {
            throw new DecryptionException('Invalid structure token', 500);
        }
    }

    /**
     * @throws Throwable
     */
    public static function generateEncryptionKey(): string
    {
        return bin2hex(random_bytes(32));
    }
}