<?php

// ------- CONFIGURAÇÕES  ----------------

$appUrl = "http://localhost";

$dbHost = "localhost";
$dbName = "mikrotik";
$dbUser = "user";
$dbPass = "nilson";
$dbPort = 3306;

// ------- CONFIGURAÇÕES  ----------------



// INCLUI CLASSES NA PAGINA
use App\Http\Middleware\Queue;
use App\Database\Database;
use App\View\View;


// INICIA A SESSÃO
(new \App\Session\Login)::getStatus();

if (isset($_SESSION['user'])) {

    define("USER_INFO_ID"      , $_SESSION['user']['information']['id']);
    define("USER_INFO_FULLNAME", $_SESSION['user']['information']['fullname']);
    define("USER_INFO_USERNAME", $_SESSION['user']['information']['username']);

} else {
    unset($_SESSION['user']);
}


// CONSTANTES DE APPLICATION
define("URL", $appUrl);

define("APP_DESCRIPTION",  "MikroTik API Gateway");
define("APP_AUTHOR",       "Nilson Pessim by TechLabs Technology");
define("APP_TIMEZONE",     "America/Sao_Paulo");
define("APP_DEV_HOMEPAGE", "https://github.com/nilsonpessim");
define("APP_VERSION",      "v0.0.1");

define("ASSETS",  URL . '/view/assets');


// CONFIGURAÇÃO DO BANCO DE DADOS
Database::config($dbHost, $dbName, $dbUser, $dbPass, $dbPort);


// CONFIGURAÇÃO DA TIMEZONE
date_default_timezone_set(APP_TIMEZONE);


// INICIA A VIEW
View::init(
    ['URL' => URL, 'ASSETS' => ASSETS]
);


// MAPEAMENTO DE MIDDLEWARES
Queue::setMap([
    'maintenance'          => \App\Http\Middleware\Maintenance::class,
    'license'              => \App\Http\Middleware\License::class,
    'require-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
    'require-admin-login'  => \App\Http\Middleware\RequireAdminLogin::class,
    'rest'                 => \App\Http\Middleware\Rest::class,
    'user-basic-auth'      => \App\Http\Middleware\UserBasicAuth::class,
]);


// MAPEAMENTO DE MIDDLEWARES PADRÕES (EXECUTADO EM TODAS AS ROTAS)
Queue::setDefault([
    'maintenance',
    'license'
]);

