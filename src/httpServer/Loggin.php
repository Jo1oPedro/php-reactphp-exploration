<?php

namespace Reactphp\App\httpServer;

use Psr\Http\Message\ServerRequestInterface;

final class loggin
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        echo 'Method: ' . $request->getMethod() . ' path: ' . $request->getUri()->getPath() . PHP_EOL;
        return $next($request);
    }
}