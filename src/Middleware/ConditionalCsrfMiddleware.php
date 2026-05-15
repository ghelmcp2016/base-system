<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ConditionalCsrfMiddleware implements MiddlewareInterface
{
    private CsrfProtectionMiddleware $csrfMiddleware;

    public function __construct()
    {
        $this->csrfMiddleware = new CsrfProtectionMiddleware([
            'httponly' => true,
        ]);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        
        // Skip CSRF for API routes
        if (strpos($path, '/api/') === 0) {
            return $handler->handle($request);
        }

        // Apply CSRF for other routes
        return $this->csrfMiddleware->process($request, $handler);
    }
}