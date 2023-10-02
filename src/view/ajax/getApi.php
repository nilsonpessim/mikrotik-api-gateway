<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Helper\CSRF;
use App\Model\Api;

$tokenCSRF  = (isset($_POST['token'])) ? $_POST['token'] : false;
$idApi      = (isset($_POST['idApi'])) ? $_POST['idApi'] : false;

if ($tokenCSRF && $idApi) { 

    if (!(new CSRF)::verifyToken($tokenCSRF)) {
        echo json_encode(["error" => "error"]);
        exit;
    }

    $obApi = Api::getUserById($idApi);
    if (!$obApi instanceof Api) {
        echo json_encode(["error" => "error"]);
        exit;
    }

    echo json_encode($obApi);

} else {
    echo json_encode(["error" => "error"]);
}