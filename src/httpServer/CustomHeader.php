<?php

namespace Reactphp\App\httpServer;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class CustomHeader
{
    public function __construct(
        private string $title,
        private string $value
    ) {}

    public function __invoke(RequestInterface $request, callable $next)
    {
        /** @var ResponseInterface $response */
        $response = $next($request);

        return $response->withHeader($this->title, $this->value);
    }
}