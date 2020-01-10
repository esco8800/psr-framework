<?php

use App\Http\Middleware\NotFoundHandler;
use Zend\Diactoros\ServerRequestFactory;
use Aura\Router\RouterContainer;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\ActionResolver;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Framework\Http\Router\Exceptions\RequestNotMatchedException;
use Zend\Diactoros\Response\HtmlResponse;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Init

$params = [
    'users' => ['admin' => '123'],
];

$aura = new RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', App\Http\Action\HelloAction::class);
$routes->get('about', '/about', App\Http\Action\AboutAction::class);
$routes->get('cabinet', '/cabinet', [
    new App\Http\Middleware\BasicAuthMiddleware($params['users']),
    App\Http\Action\CabinetAction::class
]);



$router = new AuraRouterAdapter($aura);
$resolver = new ActionResolver();


### Running

$request = ServerRequestFactory::fromGlobals();
$pipeline = new \Framework\Http\Pipeline\Pipeline;

try {
    $result = $router->match($request);

    foreach ($result->getAttributes() as $attribute => $value){
        $request = $request->withAttribute($attribute, $value);
    }

    $handlers = $result->getHandler();

    foreach (is_array($handlers) ? $handlers : [$handlers] as $handler){
        $pipeline->pipe($resolver->resolve($handler));
    }

} catch (RequestNotMatchedException $e) {}

$response = $pipeline($request, new App\Http\Middleware\NotFoundHandler);

### Postprocess

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);