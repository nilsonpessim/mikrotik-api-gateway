<?php 

namespace App\Http\Middleware;

use App\Session\Login;
use App\Model\User;

class RequireAdminLogin{

    public function handle($request, $next)
    {
        //VERIFICA SE O USUÁRIO ESTÁ LOGADO
        if(!Login::isLogged()){
            $request->getRouter()->redirect('/auth/login');
        }

        //CONTINUA A EXECUTAÇÃO
        return $next($request);
    }
}