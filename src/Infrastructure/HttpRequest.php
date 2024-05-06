<?php

namespace Dm1tru\Barcoder\Infrastructure;

use Dm1tru\Barcoder\Application\RequestInterface;
use Dm1tru\Barcoder\Domain\ValueObject\Token;

class HttpRequest implements RequestInterface
{
    public function __construct()
    {

    }

    public function getPath(): array
    {
        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        if (str_ends_with($uri, '/')) {
            $uri = substr($uri, 0, -1);
        }

        $path = explode('/', $uri);
        array_splice($path, 0, 2);
        return $path;
    }

    public function getAuthToken(): Token
    {
        $token = '';

        if (isset($_SERVER['HTTP_X_API_KEY'])) {
            $token = $_SERVER['HTTP_X_API_KEY'];
        }

        if (!$token && isset($_GET['X-Api-Key'])) {
            $token = $_GET['X-Api-Key'];
        }

        return new Token($token);
    }
}