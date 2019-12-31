<?php

namespace Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;

class BasicAuthMiddleware
{

    CONST ATTRIBUTE = '_username';

    /**
     * @var array
     */
    private $users;

    /**
     * BasicAuthMiddleware constructor.
     * @param array $users
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return EmptyResponse
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {

        $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
        $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;

        if (!empty($username) && !empty($password)){
            foreach ($this->users as $name => $pass) {
                if ($username === $name && $password === $password) {
                     return $next($request->withAttribute(self::ATTRIBUTE, $name));
                }
            }
        }

        return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Restrict area']);

    }

}