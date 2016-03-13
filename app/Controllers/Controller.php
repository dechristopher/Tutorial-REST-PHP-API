<?php

namespace App\Controllers;

class Controller
{
    protected $request;

    protected $response;

    protected $container;

    public function __construct($request, $response, $container)
    {
        $this->request = $request;
        $this->response = $response;
        $this->container = $container;
    }

    public function response($content = '', $httpStatus = 200)
    {
        $body = $this->response->getBody();
        $body->write($content);

        return $this->response->withStatus($httpStatus)->withBody($body);
    }
}
