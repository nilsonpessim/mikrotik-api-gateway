<?php 

namespace App\Http\Middleware;

class Rest{

    public function handle($request, $next)
    {
        $request->getRouter()->setContentType('application/json');

        //EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }

}