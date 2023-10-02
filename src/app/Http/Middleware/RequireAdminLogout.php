<?php 

namespace App\Http\Middleware;

use App\Session\Login;

class RequireAdminLogout{

    public function handle($request, $next)
    {
        //VERIFICA SE O USUÁRIO ESTÁ LOGADO
        if(Login::isLogged()){
            $request->getRouter()->redirect('/home');
        }

        //CONTINUA A EXECUTAÇÃO
        return $next($request);
    }
}