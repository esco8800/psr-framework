<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Next
{
    /**
     * @var \SplQueue
     */
    private $queue;

    /**
     * @var callable $default
     */
    private $default;

    /**
     * Next constructor.
     * @param \SplQueue $queue
     * @param callable $default
     */
    public function __construct(\SplQueue $queue, callable $default)
    {
        $this->queue = $queue;
        $this->default = $default;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return ($this->default)($request);
        }

        $current = $this->queue->dequeue();

        return $current($request, function (ServerRequestInterface $request) {
            return $this($request);
        });

    }

}