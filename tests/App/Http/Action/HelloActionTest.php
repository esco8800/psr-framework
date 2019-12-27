<?php

namespace App\Http\Action;

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class HelloActionTest extends TestCase
{
    public function testGuest()
    {
        $action = new HelloAction();
        $request = new ServerRequest();

        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('Hello Guest', $response->getBody()->getContents());
    }
}