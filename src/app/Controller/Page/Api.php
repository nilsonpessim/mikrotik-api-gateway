<?php 

namespace App\Controller\Page;

use App\Model\Api as EntityApi;
use App\Model\MikroTik as EntityMikroTik;
use App\Helper;
use App\View\View;

class Api extends Page{

    private static function getStatus($request)
    {
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        return match ($queryParams['status']) {
            'created'  => Helper\Alert::getSuccess('As informações foram cadastradas!'),
            'updated'  => Helper\Alert::getSuccess('As informações foram atualizadas!'),
            'deleted'  => Helper\Alert::getSuccess('As informações foram excluídas!'),
            'mikrotik' => Helper\Alert::getError('É necessário informar pelo menos 1 MikroTik'),
            'token'    => Helper\Alert::getError('Sessão expirada, tente novamente'),
            default    => ""
        };
        
    }

    public static function getUser($request)
    {
        $content = View::render('/api/home',[
            'title'       => 'Usuários da API REST',
            'table_itens' => self::getUserItems($request),
            'status'      => self::getStatus($request),
        ]);

        return parent::getPage('Usuários da API REST', $content);
    }

    private static function getUserItems($request)
    {
        $itens = '';

        $results = EntityApi::getUsers();
        
        while($obAPI = $results->fetchObject(EntityApi::class)){

            if($obAPI->status == '1'){
                $status_color = 'success';
                $status_message = 'Habilitado';
            } else {
                $status_color = 'danger';
                $status_message = 'Desabilitado';
            }

            $itens .= View::render('/api/table-itens',[
                'id'             => $obAPI->id,
                'fullname'       => $obAPI->fullname,
                'username'       => $obAPI->username,
                'password'       => $obAPI->password,
                'status_color'   => $status_color,
                'status_message' => $status_message
            ]);
        }

        return $itens;
    }

    public static function getMikrotikItens($request)
    {
        $itens = '';

        $results = EntityMikroTik::getMikroTik(null, "fullname asc");

        while($obMikrotik = $results->fetchObject(EntityMikroTik::class)){

            $itens .= View::render('/api/mikrotik',[
                'id'    => $obMikrotik->id,
                'name'  => $obMikrotik->code,
                'value' => $obMikrotik->fullname,
            ]);

        }

        return $itens;
    }

    public static function getNewUser($request)
    {
        $content = View::render('/api/new',[
            'title'    => 'Cadastrar Usuário',
            'status'   => self::getStatus($request),
            'mk_itens' => self::getMikrotikItens($request),
            'csrf'     => Helper\CSRF::generateToken()
        ]);

        return parent::getPage('Cadastrar Usuário', $content,'users');
    }

    public static function setNewUser($request)
    {
        $postVars = $request->getPostVars();
        $keyPostVars = array_keys($postVars);

        if (!Helper\CSRF::verifyToken($postVars['csrf'])) {
            $request->getRouter()->redirect('/api/new?status=token');
        }

        $fullname = (isset($postVars['fullname'])) ? $postVars['fullname'] : bin2hex(random_bytes(6));

        $obAPIName  = EntityApi::getUserByFullname($fullname);

        if($obAPIName instanceof EntityApi){
            $request->getRouter()->redirect('/api/new?status=duplicated');
        }

        $obMikroTikItens = EntityMikroTik::getMikroTik();
        foreach ($obMikroTikItens as $key => $value) {
        
            if(in_array($value['code'],$keyPostVars)){
                $itens[] = $value['id'];
            }

        }

        if(!isset($itens)){
            $request->getRouter()->redirect('/api/new?status=mikrotik');
        }

        $obAPI = new EntityApi;
        
        $obAPI->fullname = (new Helper\ConvertValue($fullname))->setString();
        $obAPI->username = bin2hex(random_bytes(5));
        $obAPI->password = bin2hex(random_bytes(15));
        $obAPI->mikrotik = join(",",$itens);
        $obAPI->ipv4     = "*";
        $obAPI->ipv6     = "*";
        $obAPI->status   = 1;

        $obAPI->cadastrar();

        $request->getRouter()->redirect('/api/?status=created');
    }

    public static function getInformation($request,$id)
    {
        if (!is_numeric($id)) {
            $request->getRouter()->redirect('/api/');
        }

        $obAPI = EntityApi::getUserById($id);

        if(!$obAPI instanceof EntityApi){
            $request->getRouter()->redirect('/api/');
        }

        if($obAPI->status == '1'){
            $status_color = 'success';
            $status_message = 'Habilitado';
        } else {
            $status_color = 'danger';
            $status_message = 'Desabilitado';
        }

        $content = View::render('/api/info',[
            'title'       => 'Informações do Usuário',
            'id'          => $obAPI->id,
            'fullname'    => $obAPI->fullname,
            'username'    => $obAPI->username,
            'password'    => $obAPI->password,
            'ipv4'           => $obAPI->ipv4,
            'ipv6'           => $obAPI->ipv6,
            'status_color'   => $status_color,
            'status_message' => $status_message,
            'mk_itens'       => self::getMikrotikItens($request),
            'status'         => self::getStatus($request),
            'csrf'           => Helper\CSRF::generateToken()
        ]);

        return parent::getPage('Informações do Usuário', $content);
    }

    public static function setInformation($request,$id)
    {
        $postVars = $request->getPostVars();

        if (!Helper\CSRF::verifyToken($postVars['csrf'])) {
            $request->getRouter()->redirect('/api/'.$id.'/info?status=token');
        }

        $obAPI = EntityApi::getUserById($id);

        $fullname = $postVars['api_edit_fullname'];
        $ipv4     = $postVars['api_edit_ipv4'];
        $ipv6     = $postVars['api_edit_ipv6'];

        $obAPI->id       = $obAPI->id;
        $obAPI->fullname = (new Helper\ConvertValue($fullname))->setString();
        $obAPI->ipv4     = $ipv4;
        $obAPI->ipv6     = $ipv6;
        $obAPI->atualizar();

        $request->getRouter()->redirect('/api/'.$id.'/info?status=updated');
    }

    public static function setStatus($request,$id)
    {
        $postVars = $request->getPostVars();

        if (!Helper\CSRF::verifyToken($postVars['csrf'])) {
            $request->getRouter()->redirect('/api/'.$id.'/info?status=token');
        }

        $obAPI = EntityApi::getUserById($id);

        $status   = (isset($postVars['api_edit_status'])) ? 1 : 0;

        $obAPI->id     = $obAPI->id;
        $obAPI->status = $status;
        $obAPI->atualizarStatus();

        $request->getRouter()->redirect('/api/'.$id.'/info?status=updated');
    }

    public static function setMikroTik($request,$id)
    {
        $postVars = $request->getPostVars();
        $keyPostVars = array_keys($postVars);

        if (!Helper\CSRF::verifyToken($postVars['csrf'])) {
            $request->getRouter()->redirect('/api/'.$id.'/info?status=token');
        }

        $obAPI = EntityApi::getUserById($id);

        $obMikroTikItens = EntityMikroTik::getMikroTik();
        foreach ($obMikroTikItens as $key => $value) {
            if(in_array($value['code'],$keyPostVars)){
                $itens[] = $value['id'];
            }
        }

        if(!isset($itens)){
            $request->getRouter()->redirect('/api/'.$id.'/info?status=mikrotik');
        } 

        $obAPI->id       = $obAPI->id;
        $obAPI->mikrotik = join(",",$itens);
        $obAPI->atualizarMikroTik();

        $request->getRouter()->redirect('/api/'.$id.'/info?status=updated');
    }

}