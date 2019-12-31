<?php

namespace Http\Pipeline;

use Http\Pipeline\Next;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Pipeline
{
    /**
     * @var array
     */
    private $middlewareQueue;

    public function __construct()
    {
        $this->middlewareQueue = new \SplQueue();
    }

    public function pipe(callable $middleware): void
    {
        $this->middlewareQueue->enqueue($middleware);

    }

    public function __invoke(ServerRequestInterface $request, callable $default): ResponseInterface
    {
        $delegate = new Next($this->middlewareQueue, $default);

        return $delegate($request);
    }


}