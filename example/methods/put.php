<?php 

include __DIR__ . "/vendor/autoload.php";
use GuzzleHttp\RequestOptions;

PUT("http://localhost/rest/mikrotik/1/ip/address", ["address" => "192.168.0.250/24", "interface" => "ether1"]);

function PUT($URL, $DATA)
{
    $auth = new \GuzzleHttp\Client(['auth' => ["user", "password"], 'verify' => false]);
    $response = $auth->request("PUT", $URL, [RequestOptions::JSON => $DATA]);

    if ($response->getStatusCode() == 200) {
        echo $response->getBody();
    }
}
