<?php 

use App\Http\Response;
use App\Controller\Page\Login;

//ROTA DE LOGIN
$obRouter->get('/auth/login',[
    'middlewares' => [
        'require-admin-logout'
    ],
    function($request){
        return new Response(200,Login::getLoginPage($request));
    }
]);

//ROTA DE LOGIN (POST)
$obRouter->post('/auth/login',[
    'middlewares' => [
        'require-admin-logout'
    ],
    function($request){
        return new Response(200,Login::setLoginPage($request));
    }
]);

//ROTA DE LOGOUT
$obRouter->get('/auth/logout',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,Login::setLogout($request));
    }
]);