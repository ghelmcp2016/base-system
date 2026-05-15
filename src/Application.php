<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use App\Middleware\HostHeaderMiddleware;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Event\EventManagerInterface;
use Cake\Http\BaseApplication;
use App\Middleware\SessionAttributeMiddleware;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Authentication\Middleware\AuthenticationMiddleware;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceProviderInterface;
use Psr\Http\Message\ServerRequestInterface;
use Authentication\AuthenticationServiceInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 *
 * @extends \Cake\Http\BaseApplication<\App\Application>
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        $this->addPlugin('Authentication');
        $this->addPlugin('Authorization');

        // By default, does not allow fallback classes.
        FactoryLocator::add('Table', (new TableLocator())->allowFallbackClass(false));
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue

            // Error Handler
            ->add(new ErrorHandlerMiddleware(
                Configure::read('Error'),
                $this
            ))

            // Host Header Protection
            ->add(new HostHeaderMiddleware())

            // Static Assets
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Routing
            ->add(new RoutingMiddleware($this))

            // Body Parser
            ->add(new BodyParserMiddleware())

            // Session attribute for authentication persistence
            ->add(new SessionAttributeMiddleware())

            // Authentication
            ->add(new AuthenticationMiddleware($this))

            // CSRF
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
            ]));

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/5/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
        // Allow your Tables to be dependency injected
        //$container->delegate(new \Cake\ORM\Locator\TableContainer());
    }

    /**
     * Register custom event listeners here
     *
     * @param \Cake\Event\EventManagerInterface $eventManager
     * @return \Cake\Event\EventManagerInterface
     * @link https://book.cakephp.org/5/en/core-libraries/events.html#registering-listeners
     */
    public function events(EventManagerInterface $eventManager): EventManagerInterface
    {
        // $eventManager->on(new SomeCustomListenerClass());

        return $eventManager;
    }

    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService([
            'unauthenticatedRedirect' => '/login',
            'queryParam' => 'redirect',
        ]);

        // Session authenticator — checks if user is already logged in
        $service->loadAuthenticator('Authentication.Session');

        // Form authenticator — the identifier config goes INSIDE here in v4
        $service->loadAuthenticator('Authentication.Form', [
            'fields' => [
                'username' => 'email',
                'password' => 'password',
            ],
            'loginUrl' => '/login',
            'identifier' => [
                'className' => 'Authentication.Password',
                'fields' => [
                    'username' => 'email',
                    'password' => 'password',
                ],
                'resolver' => [
                    'className' => 'Authentication.Orm',
                    'userModel' => 'Users',
                ],
            ],
        ]);

        return $service;
    }
    
}
