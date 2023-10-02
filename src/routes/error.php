<?php 

use App\Http\Response;
use App\Controller\Page\Error;

//ROTA ERROR 404
$obRouter->get('/404',[
    'middlewares' => [
        'require-admin-login'
    ],
    function(){
        return new Response(200,Error::getError('404'));
    }
]);

//ROTA ERROR 500
$obRouter->get('/500',[
    'middlewares' => [
        'require-admin-login'
    ],
    function(){
        return new Response(200,Error::getError('500'));
    }
]);

/*
$obRouter->get('/{error}',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request,$error){
        return new Response(200,Error::getError('404'));
    }
]);
*/