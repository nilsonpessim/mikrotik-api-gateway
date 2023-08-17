<?php 

include __DIR__ . "/vendor/autoload.php";
use GuzzleHttp\RequestOptions;

PATCH("http://localhost/rest/mikrotik/1/ip/address/*7", ["comment" => "nilson22"]);

function PATCH($URL, $DATA)
{
    $auth = new \GuzzleHttp\Client(['auth' => ["user", "password"], 'verify' => false]);
    $response = $auth->request("PATCH", $URL, [RequestOptions::JSON => $DATA]);

    if ($response->getStatusCode() == 200) {
        echo $response->getBody();
    }
}
