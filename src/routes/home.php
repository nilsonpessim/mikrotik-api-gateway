<?php 

use App\Http\Response;
use App\Controller\Page\Home;

//ROTA DE HOME
$obRouter->get('/',[
    'middlewares' => [
        'require-admin-login'
    ],
    function($request){
        return (HOME) ? new Response(200,Home::getHome($request)) : $request->getRouter()->redirect('/mikrotik');
    }
]);