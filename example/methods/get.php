<?php 

include __DIR__ . "/vendor/autoload.php";

GET("http://localhost/rest/mikrotik/4/ip/address");

function GET($URL)
{
    $auth = new \GuzzleHttp\Client(['auth' => ["user", "password"], 'verify' => false]);
    $response = $auth->request("GET", $URL);

    if ($response->getStatusCode() == 200) {
        echo $response->getBody();
    }
}