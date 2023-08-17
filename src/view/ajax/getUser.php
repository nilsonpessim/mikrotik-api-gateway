<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Helper\CSRF;
use App\Model\User;

$tokenCSRF = (isset($_POST['token']))  ? $_POST['token']  : false;
$idUser    = (isset($_POST['idUser'])) ? $_POST['idUser'] : false;

if ($tokenCSRF && $idUser) { 

    if (!(new CSRF)::verifyToken($tokenCSRF)) {
        echo json_encode(["error" => "error"]);
        exit;
    }

    $obUser = User::getUserById($idUser);
    if (!$obUser instanceof User) {
        echo json_encode(["error" => "error"]);
        exit;
    }

    echo json_encode($obUser);

} else {
    echo json_encode(["error" => "error"]);
}