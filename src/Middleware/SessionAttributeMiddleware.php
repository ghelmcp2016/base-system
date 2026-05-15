<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Http\ServerRequest;
use Cake\Http\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that ensures a CakePHP session object is available as the
 * `session` request attribute for authentication persistence.
 */
class SessionAttributeMiddleware implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$request->getAttribute('session')) {
            if ($request instanceof ServerRequest) {
                $session = $request->getSession();
            } else {
                $session = new Session();
            }
            $request = $request->withAttribute('session', $session);
        }

        return $handler->handle($request);
    }
}
