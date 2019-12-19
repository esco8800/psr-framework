<?php

use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Init

$request = ServerRequestFactory::fromGlobals();

### Action

$name = $request->getQueryParams()['Name'] ?? 'Guest';
$response = (new HtmlResponse('Hello ' . $name));

### Sending

echo $response->getBody() . PHP_EOL;