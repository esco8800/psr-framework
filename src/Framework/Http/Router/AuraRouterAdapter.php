<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exceptions\RequestNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;
use Aura\Router\RouterContainer;
use Aura\Router\Exception\RouteNotFound;
use Framework\Http\Router\Exceptions\RouteNotFoundException;

class AuraRouterAdapter implements Router
{

    /**
     * @var RouterContainer
     */
    private $aura;

    /**
     * AuraRouterAdapter constructor.
     * @param RouterContainer $aura
     */
    public function __construct(RouterContainer $aura)
    {
        $this->aura = $aura;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Result
     */
    public function match(ServerRequestInterface $request): Result
    {
        $matcher = $this->aura->getMatcher();
        if ($route = $matcher->match($request)) {
            return new Result($route->name, $route->handler, $route->attributes);
        }
        throw new RequestNotMatchedException($request);
    }

    /**
     * @param $name
     * @param array $params
     * @return string
     */
    public function generate($name, array $params): string
    {
        $generator = $this->aura->getGenerator();
        try {
            return $generator->generate($name, $params);
        } catch (RouteNotFound $e) {
            throw new RouteNotFoundException($name, $params, $e);
        }
    }

}