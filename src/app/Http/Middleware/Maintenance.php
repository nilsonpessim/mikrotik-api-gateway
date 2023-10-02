<?php 

namespace App\Http\Middleware;

class Maintenance{

    private $maintenance = false;

    public function handle($request, $next)
    {
        //VERIFICA O ESTADO DE MANUTENÇÃO DA PAGINA
        if($this->maintenance == true){
            throw new \Exception("<h3> Estamos em manutenção. Tente novamente mais tarde </h3>", 200);
        }

        //EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }

}