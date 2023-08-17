<?php 

namespace App\Http\Middleware;

class License{

    private $license = false;

    public function handle($request, $next)
    {

        if($this->license == true){
            throw new \Exception(json_encode(["error" => "Sua licença expirou, entre em contato com o administrador"]), 200);
        }

        //EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }

}