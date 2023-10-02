<?php 

include __DIR__ . "/vendor/autoload.php";
use GuzzleHttp\RequestOptions;

POST("http://localhost/rest/mikrotik/1/system/identity/set", ["name" => "TechLabs"]);

function POST($URL, $DATA)
{
    $auth = new \GuzzleHttp\Client(['auth' => ["user", "password"], 'verify' => false]);
    $response = $auth->request("POST", $URL, [RequestOptions::JSON => $DATA]);

    if ($response->getStatusCode() == 200) {
        echo $response->getBody();
    }
}