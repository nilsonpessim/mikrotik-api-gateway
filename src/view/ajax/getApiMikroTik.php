<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Helper\CSRF;
use App\Model\MikroTik;
use App\Model\Api;

$tokenCSRF  = (isset($_POST['token'])) ? $_POST['token'] : false;
$idApi      = (isset($_POST['idApi'])) ? $_POST['idApi']    : false;

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

    foreach (MikroTik::getMikroTik() as $itemTypes) {
        $it[] = [
           $itemTypes['code'] => getItens($itemTypes['id'], $obApi->mikrotik)
        ];
    }

    echo json_encode($it);
    
} else {
    echo json_encode(["error" => "error"]);
}

function getItens($item, $object)
{
    $itens = explode(",",$object);
    return (in_array($item,$itens)) ? true : false;
}

