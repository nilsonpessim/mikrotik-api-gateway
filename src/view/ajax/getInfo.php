<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Helper\CSRF;
use App\Controller\Page\MikroTik;

$tokenCSRF  = (isset($_POST['token']))      ? $_POST['token']      : false;
$idMikroTik = (isset($_POST['idMikroTik'])) ? $_POST['idMikroTik'] : false;

if ($tokenCSRF && $idMikroTik) {

    if (!(new CSRF)::verifyToken($tokenCSRF)) {
        echo json_encode(["error" => "error"]);
        exit;
    }

    $resource = (json_decode(MikroTik::connectMikrotik($idMikroTik)->get("/system/resource")));

    if ($resource->success == "true") {

        $cpu  = $resource->result->{'cpu-load'}."%";
        $ram  = number_format((($resource->result->{'free-memory'} / $resource->result->{'total-memory'}) * 100), 1, ".")."%";
        
        echo json_encode([
            "cpu"           => $cpu,
            "ram"           => $ram,
            "info_cpu"      => $cpu,
            "info_ram"      => $ram,
            "info_board"    => $resource->result->{'board-name'},
            "info_version"  => $resource->result->{'version'},
            "info_uptime"   => $resource->result->{'uptime'},
        ]);
    
    } else {

        echo json_encode([
            "cpu"           => "0",
            "ram"           => "0",
            "info_cpu"      => "0",
            "info_ram"      => "0",
            "info_board"    => "FALHA NA API",
            "info_version"  => "FALHA NA API",
            "info_uptime"   => "FALHA NA API",
            "status"        => false
        ]);
    }

} else {
    echo json_encode(["error" => "error"]);
}