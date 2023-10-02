<?php 

namespace App\Controller\Page;

use App\Model\MikroTik as EntityMikroTik;
use App\Controller\RouterOS\RouterOS;
use App\View\View;
use App\Helper;


class MikroTik extends Page{

    private static function getStatus($request)
    {
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        return match ($queryParams['status']) {
            'created'    => Helper\Alert::getSuccess('As informações foram cadastradas!'),
            'updated'    => Helper\Alert::getSuccess('As informações foram atualizadas!'),
            'duplicated' => Helper\Alert::getError('O Nome do Roteador ou Host API, já existem em outro registro. Tente Novamente!'),
            'token'      => Helper\Alert::getError('Sessão expirada, tente novamente'),
            default      => ""
        };
    }

    public static function getMikroTik($request)
    {
        $content = View::render('/mikrotik/home',[
            'title'       => 'Lista de Roteadores MikroTik',
            'table_itens' => self::getMikroTikItems($request),
            'status'      => self::getStatus($request),
        ]);

        return parent::getPage('Lista de Roteadores MikroTik', $content);
    }

    private static function getMikroTikItems($request)
    {
        $itens = '';

        $results = EntityMikroTik::getMikroTik();
        
        while($obMikroTik = $results->fetchObject(EntityMikroTik::class)){

            if($obMikroTik->status == '1'){
                $status_color = 'success';
                $status_message = '<i class="fa-solid fa-check"></i> Ativo';
            } else {
                $status_color = 'danger';
                $status_message = '<i class="fa-solid fa-xmark"></i> Inativo';
            }

            $itens .= View::render('/mikrotik/table-itens',[
                'id'             => $obMikroTik->id,
                'host'           => $obMikroTik->host,
                'fullname'       => $obMikroTik->fullname,
                'username'       => $obMikroTik->username,
                'code'           => $obMikroTik->code,
                'status_color'   => $status_color,
                'status_message' => $status_message
            ]);
        }

        return $itens;
    }

    public static function getNewMikroTik($request)
    {
        $content = View::render('/mikrotik/new',[
            'title'    => 'Cadastrar MikroTik',
            'status'   => self::getStatus($request),
            'csrf'     => Helper\CSRF::generateToken()
        ]);

        return parent::getPage('Cadastrar MikroTik', $content);
    }

    public static function setNewMikroTik($request)
    {
        $postVars = $request->getPostVars();

        if (!Helper\CSRF::verifyToken($postVars['csrf'])) {
            $request->getRouter()->redirect('/mikrotik/new?status=token');
        }

        $host     = $postVars['host'];
        $fullname = $postVars['fullname'];
        $username = $postVars['username'];
        $password = $postVars['password'];

        $obMikroTik      = EntityMikroTik::getMikroTikByHost($host);
        $obMikroTikName  = EntityMikroTik::getMikroTikByFullname($fullname);

        if($obMikroTik instanceof EntityMikroTik){
            $request->getRouter()->redirect('/mikrotik/new?status=duplicated');
        }

        if($obMikroTikName instanceof EntityMikroTik){
            $request->getRouter()->redirect('/mikrotik/new?status=duplicated');
        }

        $obMikroTik = new EntityMikroTik;
        $obMikroTik->host        = $host;
        $obMikroTik->fullname    = (new Helper\ConvertValue($fullname))->setString();
        $obMikroTik->code        = (new Helper\ConvertValue($fullname))->setString(99);
        $obMikroTik->username    = $username;
        $obMikroTik->password    = $password;
        $obMikroTik->certificate = "false";
        $obMikroTik->status      = 1;
        
        $obMikroTik->cadastrar();

        $request->getRouter()->redirect('/mikrotik/?status=created');
    }

    public static function getInformation($request,$id)
    {
        if (!is_numeric($id)) {
            $request->getRouter()->redirect('/mikrotik/');
        }

        $obMikroTik = EntityMikroTik::getMikroTikById($id);

        if(!$obMikroTik instanceof EntityMikroTik){
            $request->getRouter()->redirect('/mikrotik');
        }

        $content = View::render('/mikrotik/info',[
            'title'      => 'Informações do MikroTik',
            'id'         => $obMikroTik->id,
            'host'       => $obMikroTik->host,
            'fullname'   => $obMikroTik->fullname,
            'username'   => $obMikroTik->username,
            'password'   => $obMikroTik->password,
            'code'       => $obMikroTik->code,
            'csrf'       => Helper\CSRF::generateToken(),
            'status'     => self::getStatus($request),
            'button_api' => self::button_api($obMikroTik->id)
        ]);

        return parent::getPage('Informações do MikroTik', $content);
    }

    public static function setInformation($request,$id)
    {
        $postVars = $request->getPostVars();

        if (!Helper\CSRF::verifyToken($postVars['csrf'])) {
            $request->getRouter()->redirect('/mikrotik/'.$id.'/info?status=token');
        }

        $host     = $postVars['mikrotik_edit_host'];
        $fullname = $postVars['mikrotik_edit_fullname'];
        $username = $postVars['mikrotik_edit_username'];
        $password = $postVars['mikrotik_edit_password'];
        $status   = (isset($postVars['mikrotik_edit_status'])) ? 1 : 0;

        $nameMikrotik = EntityMikroTik::getMikroTikByFullname($fullname);
        if($nameMikrotik instanceof EntityMikroTik && $nameMikrotik->id != $id){
            $request->getRouter()->redirect('/mikrotik/'.$id.'/info?status=duplicated');
        }

        $hostMikrotik = EntityMikroTik::getMikroTikByHost($host);
        if($hostMikrotik instanceof EntityMikroTik && $hostMikrotik->id != $id){
            $request->getRouter()->redirect('/mikrotik/'.$id.'/info?status=duplicated');
        }

        $obMikroTik = EntityMikroTik::getMikroTikById($id);

        $obMikroTik->id        = $obMikroTik->id;
        $obMikroTik->host      = $host;
        $obMikroTik->status    = $status;
        $obMikroTik->fullname    = (new Helper\ConvertValue($fullname))->setString();
        $obMikroTik->code        = (new Helper\ConvertValue($fullname))->setString(99);
        $obMikroTik->username  = $username;
        $obMikroTik->password  = $password;
        $obMikroTik->atualizar();

        $request->getRouter()->redirect('/mikrotik/'.$id.'/info?status=updated');
    }

    public static function getAPI($request,$id)
    {
        if (!is_numeric($id)) {
            $request->getRouter()->redirect('/mikrotik/');
        }

        $obMikroTik = EntityMikroTik::getMikroTikById($id);

        if(!$obMikroTik instanceof EntityMikroTik){
            $request->getRouter()->redirect('/mikrotik');
        }

        if($obMikroTik->status == 0){
            $request->getRouter()->redirect('/mikrotik/'.$id.'/info');
        }

        $token = Helper\CSRF::generateToken();

        $content = View::render('/mikrotik/api',[
            'title'          => 'API do MikroTik',
            'id'             => $obMikroTik->id,
            'host'           => $obMikroTik->host,
            'fullname'       => $obMikroTik->fullname,
            'username'       => $obMikroTik->username,
            'password'       => $obMikroTik->password,
            'code'           => $obMikroTik->code,
            'status'         => self::getStatus($request),
            
            'api_nav_get'    => self::nav_api('get'   ,'GET'    ,'active'),
            'api_nav_delete' => self::nav_api('delete','DELETE'),
            'api_nav_patch'  => self::nav_api('patch' ,'PATCH'),
            'api_nav_put'    => self::nav_api('put'   ,'PUT'),
            'api_nav_post'   => self::nav_api('post'  ,'POST'),

            'api_panel_get'    => self::panel_api($token, $obMikroTik->id, 'get'   , '/mikrotik/panel/get'   , 'active'),
            'api_panel_delete' => self::panel_api($token, $obMikroTik->id, 'delete', '/mikrotik/panel/delete',''),
            'api_panel_patch'  => self::panel_api($token, $obMikroTik->id, 'patch' , '/mikrotik/panel/patch' ,''),
            'api_panel_put'    => self::panel_api($token, $obMikroTik->id, 'put'   , '/mikrotik/panel/put'   , ''),
            'api_panel_post'   => self::panel_api($token, $obMikroTik->id, 'post'  , '/mikrotik/panel/post'   , ''),
        ]);

        return parent::getPage('API do MikroTik', $content);
    }

    public static function callAPI($request, $id, $method)
    {
        $postVars = $request->getPostVars();

        if (!Helper\CSRF::verifyToken($postVars['csrf'])) {
            $request->getRouter()->redirect('/mikrotik/'.$id.'/api?status=token');
        }

        $obMikroTik = EntityMikroTik::getMikroTikById($id);

        if($obMikroTik->status == 0){
            $request->getRouter()->redirect('/mikrotik/'.$id.'/info');
        }

        $routerOS = new RouterOS($obMikroTik->host, $obMikroTik->username, $obMikroTik->password);

        switch ($method) {

            case 'GET':
                $result = json_decode($routerOS->get($postVars['url']));
            break;

            case 'PATCH':
                $result = json_decode($routerOS->patch($postVars['url'], json_decode($postVars['data'])));
            break;

            case 'PUT':
                $result = json_decode($routerOS->put($postVars['url'], json_decode($postVars['data'])));
            break;

            case 'POST':
                $result = json_decode($routerOS->post($postVars['url'], json_decode($postVars['data'])));
            break;

            case 'DELETE':
                $result = ["deleted"];
            break;
            
            default:
                $result = json_decode($routerOS->get($postVars['url']));
            break;
        }

        $content = View::render('/mikrotik/result-api',[
            'title'    => 'Retorno da API',
            'id'       => $id,
            'fullname' => $obMikroTik->fullname,
            'method'   => $method,
            'result'   => json_encode($result, JSON_PRETTY_PRINT)
        ]);

        return parent::getPage('Retorno da API', $content);

    }

    public static function button_api($id)
    {
        $obMikroTik = EntityMikroTik::getMikroTikById($id);

        if($obMikroTik->status == 1){

            return View::render('/mikrotik/button/api',[
                'id' => $obMikroTik->id
            ]);

        }
    }

    public static function nav_api($method, $title, $active = '')
    {
        return View::render('/mikrotik/button/api_nav',[
            'method' => $method,
            'title'  => $title,
            'active' => $active
        ]);
    }

    public static function panel_api($token, $id, $method, $file, $active = '')
    {
        return View::render($file,[
            'id'     => $id,
            'method' => $method,
            'active' => $active,
            'csrf'   => $token
        ]);
    }

    public static function connectMikrotik($id)
    {
        $obMikroTik = EntityMikroTik::getMikroTikById($id);

        if ($obMikroTik instanceof EntityMikroTik) {

            return new RouterOS($obMikroTik->host, $obMikroTik->username, $obMikroTik->password);
        }
    }
}