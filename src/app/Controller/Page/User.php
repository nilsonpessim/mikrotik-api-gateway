<?php 

namespace App\Controller\Page;

use App\Model\User as EntityUser;
use App\View\View;
use App\Helper;

class User extends Page{

    private static function getStatus($request)
    {
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        return match ($queryParams['status']) {
            'created'  => Helper\Alert::getSuccess('As informações foram cadastradas!'),
            'updated'  => Helper\Alert::getSuccess('As informações foram atualizadas!'),
            'deleted'  => Helper\Alert::getSuccess('As informações foram excluídas!'),
            'token'    => Helper\Alert::getError('Sessão expirada, tente novamente'),
            default    => ""
        };

    }

    public static function getUser($request)
    {
        
        $content = View::render('/user/home',[
            'title'       => 'Usuários do Sistema WEB',
            'table_itens' => self::getUserItems($request),
            'status'      => self::getStatus($request),
        ]);

        return parent::getPage('Usuários do Sistema WEB', $content);
    }

    private static function getUserItems($request)
    {
        $itens = '';

        $results = EntityUser::getUsers();
        
        while($obUser = $results->fetchObject(EntityUser::class)){

            if($obUser->status == '1'){
                $status_color = 'success';
                $status_message = 'Habilitado';
            } else {
                $status_color = 'danger';
                $status_message = 'Desabilitado';
            }

            $itens .= View::render('/user/table-itens',[
                'id'             => $obUser->id,
                'fullname'       => $obUser->fullname,
                'username'       => $obUser->username,
                'status_color'   => $status_color,
                'status_message' => $status_message,
                'your'           => self::yourLogin($obUser->id),
            ]);
        }

        return $itens;
    }

    public static function getNewUser($request)
    {
        $content = View::render('/user/new',[
            'title'    => 'Cadastrar Usuário',
            'status'   => self::getStatus($request),
            'csrf'     => Helper\CSRF::generateToken()
        ]);

        return parent::getPage('Cadastrar Usuário', $content,'users');
    }

    public static function setNewUser($request)
    {
        $postVars = $request->getPostVars();

        if (!Helper\CSRF::verifyToken($postVars['csrf'])) {
            $request->getRouter()->redirect('/user/new?status=token');
        }

        $postVars['status'] = 1;

        $fullname      = $postVars['fullname'];
        $username      = $postVars['username'];
        $status        = $postVars['status'];
        $password      = $postVars['password'];

        $obUser      = EntityUser::getUserByEmail($username);
        $obUserName  = EntityUser::getUserByFullname($fullname);

        if($obUser instanceof EntityUser){
            $request->getRouter()->redirect('/user/new?status=duplicated');
        }

        if($obUserName instanceof EntityUser){
            $request->getRouter()->redirect('/user/new?status=duplicated');
        }

        $obUser = new EntityUser;
        $obUser->fullname     = (new Helper\ConvertValue($fullname))->setString();
        $obUser->username     = $username;
        $obUser->status       = $status;
        $obUser->password     = password_hash($password,PASSWORD_DEFAULT);
        $obUser->cadastrar();

        $request->getRouter()->redirect('/user/'.$obUser->id.'/info?status=created');
    }

    public static function getInformation($request,$id)
    {
        if (!is_numeric($id)) {
            $request->getRouter()->redirect('/user/');
        }

        $obUser = EntityUser::getUserById($id);

        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/user');
        }

        $content = View::render('/user/info',[
            'title'       => 'Informações do Usuário',
            'id'          => $obUser->id,
            'fullname'    => $obUser->fullname,
            'username'    => $obUser->username,
            'status'      => self::getStatus($request),
            'enable'      => self::enableUser($id, $obUser->status),
            'csrf'        => Helper\CSRF::generateToken()
        ]);

        return parent::getPage('Informações do Usuário', $content);
    }

    public static function setInformation($request,$id)
    {
        $postVars = $request->getPostVars();

        if (!Helper\CSRF::verifyToken($postVars['csrf'])) {
            $request->getRouter()->redirect('/user/'.$id.'/info?status=token');
        }

        $obUser = EntityUser::getUserById($id);

        $fullname = $postVars['user_edit_fullname'];
        $username = $postVars['user_edit_username'];

        $obUser->id        = $obUser->id;
        $obUser->fullname  = (new Helper\ConvertValue($fullname))->setString();
        $obUser->username  = $username;
        $obUser->atualizar();

        $request->getRouter()->redirect('/user/'.$id.'/info?status=updated');
    }

    public static function setPassword($request,$id)
    {
        $postVars = $request->getPostVars();

        if (!Helper\CSRF::verifyToken($postVars['csrf'])) {
            $request->getRouter()->redirect('/user/'.$id.'/info?status=token');
        }

        $obUser = EntityUser::getUserById($id);

        $password = $postVars['user_edit_password'];

        $obUser->id       = $obUser->id;
        $obUser->password = password_hash($password,PASSWORD_DEFAULT);
        $obUser->atualizarPassword();

        $request->getRouter()->redirect('/user/'.$id.'/info?status=updated');
    }

    public static function setStatus($request,$id)
    {
        $postVars = $request->getPostVars();

        if (!Helper\CSRF::verifyToken($postVars['csrf'])) {
            $request->getRouter()->redirect('/user/'.$id.'/info?status=token');
        }

        $obUser = EntityUser::getUserById($id);

        $status   = (isset($postVars['user_edit_status'])) ? 1 : 0;

        $obUser->id     = $obUser->id;
        $obUser->status = $status;
        $obUser->atualizarStatus();

        $request->getRouter()->redirect('/user/'.$id.'/info?status=updated');
    }

    public static function yourLogin($id)
    {
        return (USER_INFO_ID == $id) ? "<span class='badge bg-info'>Você</span>" : '';
    }

    public static function enableUser($id, $status)
    {
        if (USER_INFO_ID != $id) {

            if($status == '1'){
                $status_color = 'success';
                $status_message = 'Habilitado';
            } else {
                $status_color = 'danger';
                $status_message = 'Desabilitado';
            }

            return View::render('/user/enable', [
                'id'             => $id,
                'status_color'   => $status_color,
                'status_message' => $status_message,
            ]);
        }
    }

}