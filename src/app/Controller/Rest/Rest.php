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

        switch ($method) {
            
            case 'GET':
                $result = ['success' => 'true', 'result' => json_decode($routerOS->get($url))];
            break;

            case 'POST':
                $result = ['success' => 'true', 'result' => json_decode($routerOS->post($url, $postVars))];
            break;

            case 'PATCH':
                $result = ['success' => 'true', 'result' => json_decode($routerOS->patch($url, $postVars))];
            break;

            case 'PUT':
                $result = ['success' => 'true', 'result' => json_decode($routerOS->put($url, $postVars))];
            break;

            case 'DELETE':
                $routerOS->delete($url);
                $result = ['success' => 'true','result'  => 'deleted'];
            break;
            
            default:
                throw new \Exception("this request not valid", 400);
            break;
        }

        return $result;
    }
}