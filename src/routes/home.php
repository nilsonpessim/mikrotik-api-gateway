<?php 

use App\Http\Response;
use App\Controller\Page\Home;

//ROTA RAIZ - REDIRECIONA PARA HOME
$obRouter->get('/',[
    function($request){
        $request->getRouter()->redirect('/mikrotik');
    }
]);