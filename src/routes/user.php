<?php 

use App\Http\Response;
use App\Controller\Page\User;

//ROTA DE USUÁRIOS
$obRouter->get('/user',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,User::getUser($request));
    }
]);

//ROTA DE CRIAÇÃO DOS USUÁRIOS (GET)
$obRouter->get('/user/new',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,User::getNewUser($request));
    }
]);

//ROTA DE CRIAÇÃO DOS USUÁRIOS (POST)
$obRouter->post('/user/new',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,User::setNewUser($request));
    }
]);


//ROTA DE EDIT (GET)
$obRouter->get('/user/{id}/info',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,User::getInformation($request,$id));
    }
]);

//ROTA DE EDIT (POST)
$obRouter->post('/user/{id}/info',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,User::setInformation($request,$id));
    }
]);

//ROTA DE EDIT - PASSWORD (POST)
$obRouter->post('/user/{id}/password',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,User::setPassword($request,$id));
    }
]);

//ROTA DE EDIT - STATUS (POST)
$obRouter->post('/user/{id}/status', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,User::setStatus($request,$id));
    }
]);