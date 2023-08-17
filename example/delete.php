<?php 

include __DIR__ . "/vendor/autoload.php";

DELETE("http://localhost/rest/mikrotik/1/ip/address/*8");

function DELETE($URL)
{
    $auth = new \GuzzleHttp\Client(['auth' => ["user", "password"], 'verify' => false]);
    $response = $auth->request("DELETE", $URL);

    if ($response->getStatusCode() == 200) {
        echo $response->getBody();
    }
}