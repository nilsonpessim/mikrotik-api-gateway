<?php

// include autoload
include "vendor/autoload.php";

// include app class
$app = new App\App;

// url base
$urlBase = "http://localhost/rest";

// list mikrotik
$listMikroTik = ($app)->get("$urlBase/mikrotik")->result;

// loop
foreach ($listMikroTik as $MikroTik) {

    // create object
    $obj = (object) ['url' => $urlBase, 'app' => $app, 'id' => $MikroTik->id, 'name' => $MikroTik->fullname];

    // execute script
    execute($obj);
}

// execute
function execute($obj)
{
    // set new identity
    ($obj->app)->setIdentity($obj->url, $obj->id, $obj->name);

    // check dns and change
    ($obj->app)->changeDNS($obj->url, $obj->id);

    // check version and upgrade
    ($obj->app)->upgradeVersion($obj->url, $obj->id);

    // redirect to home
    header("Location: /");
}