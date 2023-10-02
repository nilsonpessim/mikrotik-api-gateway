<?php 

use App\Http\Response;
use App\Controller\Page\Api;

//ROTA DE USUÁRIOS
$obRouter->get('/api',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,Api::getUser($request));
    }
]);

//ROTA DE CRIAÇÃO DOS USUÁRIOS (GET)
$obRouter->get('/api/new',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,Api::getNewUser($request));
    }
]);

//ROTA DE CRIAÇÃO DOS USUÁRIOS (POST)
$obRouter->post('/api/new',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,Api::setNewUser($request));
    }
]);

//ROTA DE EDIT (GET)
$obRouter->get('/api/{id}/info',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,Api::getInformation($request,$id));
    }
]);

//ROTA DE EDIT (POST)
$obRouter->post('/api/{id}/info',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,Api::setInformation($request,$id));
    }
]);

//ROTA DE EDIT - STATUS (POST)
$obRouter->post('/api/{id}/status',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,Api::setStatus($request,$id));
    }
]);

//ROTA DE EDIT - MIKROTIK (POST)
$obRouter->post('/api/{id}/mikrotik',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,Api::setMikroTik($request,$id));
    }
]);