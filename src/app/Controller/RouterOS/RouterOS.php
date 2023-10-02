<?php 

namespace App\Controller\RouterOS;

use GuzzleHttp\RequestOptions;

class RouterOS{

    private $host;
    private $auth;
    
    public function __construct($host, $user, $pass, $cert = false)
    {
        $this->host = $host;
        $this->auth = ['auth' => [$user, $pass], 'verify' => $cert];
    }

    public function get($route)
    {
        $this->host .= $route;

        return (new \App\Helper\Exception())->getException($this->host, $this->auth, "GET");
    }

    public function patch($route, $data)
    {
        $this->host .= $route;

        return (new \App\Helper\Exception())->getException($this->host, $this->auth, "PATCH", [RequestOptions::JSON => $data]);
    }

    public function put($route, $data)
    {   
        $this->host .= $route;

        return (new \App\Helper\Exception())->getException($this->host, $this->auth, "PUT", [RequestOptions::JSON => $data]);
    }

    public function post($route, $data)
    {   
        $this->host .= $route;

        return (new \App\Helper\Exception())->getException($this->host, $this->auth, "POST", [RequestOptions::JSON => $data]);
    }

    public function delete($route)
    {   
        $this->host .= $route;

        return (new \App\Helper\Exception())->getException($this->host, $this->auth, "DELETE");
    }

}