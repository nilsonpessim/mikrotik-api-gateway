<?php

use App\Http\Response;
use App\Controller\Rest\Rest;

//ROTA DE HOME DA API
$obRouter->get('/rest',[
    'middlewares' => [
        'rest',
        'user-basic-auth'
    ],
    function($request){
        return new Response(200,Rest::getRest($request),'application/json');
    }
]);

//ROTA DE HOME DA API
$obRouter->get('/rest/mikrotik',[
    'middlewares' => [
        'rest',
        'user-basic-auth'
    ],
    function($request){
        return new Response(200,Rest::getMikrotik($request),'application/json');
    }
]);



//ROTA PARA GET
$obRouter->get('/rest/mikrotik/{id}/{uri}',[
    'middlewares' => [
        'rest',
        'user-basic-auth'
    ],
    function($request, $id, $uri){
        return new Response(200,Rest::callAPI($request, $id, "GET", "/$uri"),'application/json');
    }
]);

//ROTA PARA PATCH
$obRouter->patch('/rest/mikrotik/{id}/{uri}',[
    'middlewares' => [
        'rest',
        'user-basic-auth'
    ],
    function($request, $id, $uri){
        return new Response(200,Rest::callAPI($request, $id, "PATCH", "/$uri"),'application/json');
    }
]);

//ROTA PARA PUT
$obRouter->put('/rest/mikrotik/{id}/{uri}',[
    'middlewares' => [
        'rest',
        'user-basic-auth'
    ],
    function($request, $id, $uri){
        return new Response(200,Rest::callAPI($request, $id, "PUT", "/$uri"),'application/json');
    }
]);

//ROTA PARA POST
$obRouter->post('/rest/mikrotik/{id}/{uri}',[
    'middlewares' => [
        'rest',
        'user-basic-auth'
    ],
    function($request, $id, $uri){
        return new Response(200,Rest::callAPI($request, $id, "POST", "/$uri"),'application/json');
    }
]);

//ROTA PARA DELETE
$obRouter->delete('/rest/mikrotik/{id}/{uri}',[
    'middlewares' => [
        'rest',
        'user-basic-auth'
    ],
    function($request, $id, $uri){
        return new Response(200,Rest::callAPI($request, $id, "DELETE", "/$uri"),'application/json');
    }
]);



//ROTA PARA GET
$obRouter->get('/rest/mikrotik/{id}',[
    'middlewares' => [
        'rest',
        'user-basic-auth'
    ],
    function($request, $id){
        return new Response(200,Rest::callAPI($request, $id, "GET", "/system/resource"),'application/json');
    }
]);

//ROTA DE HOME DA API
$obRouter->get('/rest/{id}',[
    'middlewares' => [
        'rest',
        'user-basic-auth'
    ],
    function($request){
        return new Response(200,Rest::getRest($request),'application/json');
    }
]);