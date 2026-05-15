<?php
declare(strict_types=1);

namespace App\Controller\API;

use App\Controller\AppController as BaseAppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\InvalidCsrfTokenException;
use Cake\Http\Response;

class AppController extends BaseAppController
{
    public function beforeFilter(EventInterface $event): void
    {
        // API routes should not be blocked by the web session auth redirect.
        // Authentication may still be available if the user is logged in, but
        // the API should work even when the session cannot be restored.
    }

    public function beforeRender(EventInterface $event): void
    {
        // Ensure all responses from API controllers are JSON
        if ($this->request->is('json') || $this->request->accepts('application/json')) {
            $this->viewBuilder()->setClassName('Json');
        }
    }

    protected function jsonResponse(array $payload, int $status = 200): Response
    {
        return $this->response
            ->withType('application/json')
            ->withStatus($status)
            ->withStringBody((string)json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
