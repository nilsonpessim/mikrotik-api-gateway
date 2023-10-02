<?php 

use App\Http\Response;
use App\Controller\Page\MikroTik;

//ROTA DE USUÁRIOS
$obRouter->get('/mikrotik',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,MikroTik::getMikroTik($request));
    }
]);

//ROTA DE CRIAÇÃO DOS USUÁRIOS (GET)
$obRouter->get('/mikrotik/new',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,MikroTik::getNewMikroTik($request));
    }
]);

//ROTA DE CRIAÇÃO DOS USUÁRIOS (POST)
$obRouter->post('/mikrotik/new',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return new Response(200,MikroTik::setNewMikroTik($request));
    }
]);

//ROTA DE INFO (GET)
$obRouter->get('/mikrotik/{id}/info',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,MikroTik::getInformation($request,$id));
    }
]);

//ROTA DE INFO (POST)
$obRouter->post('/mikrotik/{id}/info',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,MikroTik::setInformation($request,$id));
    }
]);

//ROTA DE API (GET)
$obRouter->get('/mikrotik/{id}/api',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,MikroTik::getAPI($request,$id));
    }
]);


//SEND MIKROTIK GET (GET)
$obRouter->get('/mikrotik/{id}/call_api_get',[
    function($request, $id){
        $request->getRouter()->redirect('/mikrotik/'.$id.'/api');
    }
]);

//SEND MIKROTIK GET (POST)
$obRouter->post('/mikrotik/{id}/call_api_get',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,MikroTik::callAPI($request,$id,"GET"));
    }
]);


//SEND MIKROTIK PATCH (GET)
$obRouter->get('/mikrotik/{id}/call_api_patch',[
    function($request, $id){
        $request->getRouter()->redirect('/mikrotik/'.$id.'/api');
    }
]);

//SEND MIKROTIK PATCH (POST)
$obRouter->post('/mikrotik/{id}/call_api_patch',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,MikroTik::callAPI($request,$id,"PATCH"));
    }
]);


//SEND MIKROTIK PUT (GET)
$obRouter->get('/mikrotik/{id}/call_api_put',[
    function($request, $id){
        $request->getRouter()->redirect('/mikrotik/'.$id.'/api');
    }
]);

//SEND MIKROTIK PUT (POST)
$obRouter->post('/mikrotik/{id}/call_api_put',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,MikroTik::callAPI($request,$id,"PUT"));
    }
]);


//SEND MIKROTIK POST (GET)
$obRouter->get('/mikrotik/{id}/call_api_post',[
    function($request, $id){
        $request->getRouter()->redirect('/mikrotik/'.$id.'/api');
    }
]);

//SEND MIKROTIK POST (POST)
$obRouter->post('/mikrotik/{id}/call_api_post',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,MikroTik::callAPI($request,$id,"POST"));
    }
]);


//SEND MIKROTIK DELETE (GET)
$obRouter->get('/mikrotik/{id}/call_api_delete',[
    function($request, $id){
        $request->getRouter()->redirect('/mikrotik/'.$id.'/api');
    }
]);

//SEND MIKROTIK DELETE (POST)
$obRouter->post('/mikrotik/{id}/call_api_delete',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        return new Response(200,MikroTik::callAPI($request,$id,"DELETE"));
    }
]);


///////////////////////////////////////////////////////////////////////////////////////////////

//ROTAS DINAMICAS PARA CORREÇÃO DE ERROS

//ROTA DE INFO (GET)
$obRouter->get('/mikrotik/{id}',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$id){
        $request->getRouter()->redirect('/mikrotik/'.$id.'/info');
    }
]);