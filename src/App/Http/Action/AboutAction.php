<?php

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class AboutAction
{
    public function __invoke(ServerRequestInterface $request)
    {
        return new HtmlResponse('About page');
    }

}