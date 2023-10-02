<?php 

namespace App\Http\Middleware;

use App\Model\Api;

class UserBasicAuth{

    private function getBasicAuthUser()
    {
        if(!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW'])){
            return false;
        }

        $obUser = Api::getUserByUsername($_SERVER['PHP_AUTH_USER']);

        if (!$obUser instanceof Api) {
            return false;
        }

        (new \App\Helper\ipAddress($obUser));

        return ($obUser->password == $_SERVER['PHP_AUTH_PW']) ? $obUser : false; 
    }

    private function basicAuth($request)
    {
        if ($obUser = $this->getBasicAuthUser()) {
            $request->user = $obUser;
            return true;
        }

        throw new \Exception("user or password invalid", 403);
    }

    public function handle($request, $next)
    {
        $this->basicAuth($request);

        return $next($request);
    }

}