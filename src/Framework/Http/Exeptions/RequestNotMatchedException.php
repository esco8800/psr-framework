<?php

namespace Framework\Http\Exceptions;

use Psr\Http\Message\ServerRequestInterface;

class RequestNotMatchedException extends \LogicException
{
    private $request;

    /**
     * RequestNotMatchedException constructor.
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct('Matches not found.');
        $this->request = $request;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}