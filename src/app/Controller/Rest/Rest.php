<?php 

namespace App\Controller\Rest;

use App\Model\MikroTik;
use App\Model\Api as EntityApi;
use App\Helper\ICMP;
use App\Controller\RouterOS\RouterOS;

class Rest{

    public static function getRest($request)
    {
        return [
            'description' => APP_DESCRIPTION,
            'version'     => APP_VERSION,
            'authors'     => APP_AUTHOR,
            'homepage'    => APP_DEV_HOMEPAGE
        ];
    }

    public static function getMikroTik($request)
    {   
        foreach (explode(",",$request->user->mikrotik) as $value) {
            
            $results = Mikrotik::getMikroTik("m.id = ".$value." AND m.status = '1'");

            while($obMikroTik = $results->fetchObject(Mikrotik::class)){

                $itens[] = [
                    'id'       => $obMikroTik->id,
                    'code'     => $obMikroTik->code,
                    'fullname' => $obMikroTik->fullname,
                ];
            }

        }

        return ['success' => 'true', 'result' => $itens];
    }

    public static function callAPI($request, $id, $method, $url)
    {
        $postVars = $request->getPostVars();

        $queryParams = $request->getQueryParams();
        
        if (!empty($queryParams)) {

            if (array_key_exists("_proplist", $queryParams)) {
                $queryParams[".proplist"] = $queryParams["_proplist"];
                unset($queryParams["_proplist"]);
            }

            $url .= "?" . http_build_query($queryParams);
        }

        //print_r($url); exit;

        $arrMikroTik = explode(",",$request->user->mikrotik);

        $obMikroTik = (is_numeric($id)) ? MikroTik::getMikroTikById($id) : MikroTik::getMikroTikByCode($id);

        if (!$obMikroTik instanceof MikroTik) {
            throw new \Exception("router '{$id}' not exist", 404);
        }

        if ($obMikroTik->status == 0) {
            throw new \Exception("router '{$id}' disabled", 401);
        }

        if (!in_array($obMikroTik->id, $arrMikroTik)) {
            throw new \Exception("you do not have permission to access router id {$id}", 401);
        }

        $routerOS = new RouterOS($obMikroTik->host, $obMikroTik->username, $obMikroTik->password);

        return match ($method) {
            'GET'    => json_decode($routerOS->get($url)),
            'POST'   => json_decode($routerOS->post($url, $postVars)),
            'PATCH'  => json_decode($routerOS->patch($url, $postVars)),
            'PUT'    => json_decode($routerOS->put($url, $postVars)),
            'DELETE' => self::deleteRouterOS($url, $routerOS),
            default  => throw new \Exception("this request not valid", 400)
        };
        
    }

    private static function deleteRouterOS($url, $routerOS)
    {
        $routerOS->delete($url);
        return ['success' => 'true','result'  => 'deleted'];       
    }
}