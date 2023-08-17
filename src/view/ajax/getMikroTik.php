<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Helper\CSRF;
use App\Model\MikroTik;

$tokenCSRF  = (isset($_POST['token']))      ? $_POST['token']      : false;
$idMikroTik = (isset($_POST['idMikroTik'])) ? $_POST['idMikroTik'] : false;


if ($tokenCSRF && $idMikroTik) { 

    if (!(new CSRF)::verifyToken($tokenCSRF)) {
        echo json_encode(["error" => "error"]);
        exit;
    }

    $obMikroTik = MikroTik::getMikroTikById($idMikroTik);
    if (!$obMikroTik instanceof MikroTik) {
        echo json_encode(["error" => "error"]);
        exit;
    }

    echo json_encode($obMikroTik);

} else {
    echo json_encode(["error" => "error"]);
}