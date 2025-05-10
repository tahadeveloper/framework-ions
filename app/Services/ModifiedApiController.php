<?php

namespace App\Services;

use BadMethodCallException;
use Ions\Bundles\AppKeys;
use Ions\Bundles\Localization;
use Ions\Foundation\BluePrint;
use Ions\Foundation\Kernel;
use Ions\Foundation\RegisterDB;
use Ions\Support\JsonResponse;
use Ions\Support\Request;
use Ions\Support\Response;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

abstract class ModifiedApiController implements BluePrint
{
    protected string|object|array $inputs;
    protected mixed $method;
    protected Request $request;
    protected Response $response;
    protected string $file = 'api';
    protected string $locale = 'en';

    /**
     */
    public function __construct()
    {
        $this->response = Kernel::response();
        $this->request = Kernel::request();

        if (!$this->isAuthorized()) {
            $this->returnStructure('Unauthorized!', ResponseAlias::HTTP_UNAUTHORIZED);
        }

        RegisterDB::boot();
        $this->method = $this->request->getMethod();
        $this->request->all();
        // old way $this->inputs = (object)($globalInputs ?: $this->renderRequest($this->method));
    }

    public function _initState(Request $request): void
    {
        // Implement _initState() method.
    }

    public function _loadInit(Request $request): void
    {
        $config_locale = config('app.localization.locale', $this->locale);
        Localization::init($this->file, $config_locale);
    }

    public function _loadedState(Request $request): void
    {
        // Implement _loadedState() method.
    }

    public function _endState(Request $request): void
    {
        // Implement _endState() method.
    }

    #[NoReturn]
    protected function unauthorizedResponse($response): void
    {
        $this->returnStructure($response, ResponseAlias::HTTP_UNAUTHORIZED);
    }

    #[NoReturn]
    private function returnStructure($error, $status): void
    {
        $data = [];
        // make header status code
        http_response_code($status);
        // force reasoner to be JSON
        header('Content-Type: application/json');
        $this->display(
            collect([
                'status' => 'error',
                'status_code' => $status,
                'message' => $error,
                'data' => $data,
                'errors' => [],
            ])->toJson(),
        );
    }

    private function isAuthorized(): bool
    {
        try {
            if (!isset($_SERVER['HTTP_AUTHORIZATION']) && empty($this->request->header('Authorization'))) {
                return false;
            }

            @list(
                $authType, $authData
                ) =
                explode(" ", $_SERVER['HTTP_AUTHORIZATION'] ?? $this->request->header('Authorization'), 2);

            if ($authType !== 'Bearer') {
                $this->unauthorizedResponse('No key attach!');
            }

            $status = AppKeys::validateJWT($authData);

            return $status['success'];
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @throws \JsonException
     */
    private function renderRequest($method)
    {
        $php_input = 'php://input';
        $json_response = new JsonResponse();
        switch ($method) {
            case 'POST':
                $file_inputs = file_get_contents($php_input);
                $vars = $json_response->setContent($file_inputs)->getContent();
                if (!empty($_FILES)) {
                    $vars = (object)$vars;
                    $vars->files = $_FILES;
                }
                break;
            case 'DELETE':
            case 'GET':
                $vars = $_GET;
                $vars = (object)$vars;
                break;
            case 'PUT':
                $vars = json_decode(file_get_contents($php_input), JSON_FORCE_OBJECT, 512, JSON_THROW_ON_ERROR);
                if (empty($vars)) {
                    parse_str(file_get_contents($php_input), $vars);
                    $vars = (object)$vars;
                }
                break;
            default:
                $vars = (object)[];
                break;
        }
        return $vars;
    }

    #[NoReturn]
    public function notFoundResponse($response): bool|array|string
    {
        $this->returnStructure($response, ResponseAlias::HTTP_NOT_FOUND);
    }

    public function routeMethod($method, $callback): void
    {
        if ($callback !== null && $this->method === strtoupper($method)) {
            $callback();
        }
    }

    #[NoReturn]
    protected function display($jsonResponse): void
    {
        if (!is_string($jsonResponse)) {
            abort(500, 'Data send to api must be Json type.');
        }

        echo $jsonResponse;
        exit();
    }

    /**
     * Execute an action on the controller.
     *
     * @param string $method
     * @param array $parameters
     * @return void
     */
    public function callAction(string $method, array $parameters): void
    {
        $this->{$method}(...array_values($parameters));
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call(string $method, array $parameters)
    {
        throw new BadMethodCallException(
            sprintf(
                'Method %s::%s does not exist.',
                static::class,
                $method,
            ),
        );
    }
}