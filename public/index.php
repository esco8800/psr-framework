<?php

use Zend\Diactoros\ServerRequestFactory;
use Aura\Router\RouterContainer;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\ActionResolver;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Framework\Http\Exceptions\RequestNotMatchedException;
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
    new \Http\Middleware\BasicAuthMiddleware($params['users']),
    App\Http\Action\CabinetAction::class
]);


$router = new AuraRouterAdapter($aura);
$resolver = new ActionResolver();


### Running

$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);

    foreach ($result->getAttributes() as $attribute => $value){
        $request = $request->withAttribute($attribute, $value);
    }

    $handler = $result->getHandler();
    /** @var callable $action */
    $action = $resolver->resolve($handler);
    $response = $action($request);

} catch (RequestNotMatchedException $e) {
    $response = new HtmlResponse('Undefined page', 404);
}

### Postprocess

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);