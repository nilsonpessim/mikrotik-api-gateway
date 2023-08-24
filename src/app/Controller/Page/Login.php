<?php 

namespace App\Controller\Page;

use App\Session\Login as SessionLogin;
use App\Model\User;
use App\View\View;
use App\Helper\Alert;


class Login extends Page{

    private static function getStatus($request)
    {
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        return match ($queryParams['status']) {
            'invalid'  => Alert::getError('Usuário ou Senha Inválidos!'),
            'disabled' => Alert::getError('Usuário desabilitado, entre em contato com o administrador!'),
            default    => ""
        };
    }

    public static function getLoginPage($request)
    {
        return View::render('/login/home',[
            'description' => APP_DESCRIPTION,
            'author'      => APP_AUTHOR,
            'status'      => self::getStatus($request),
            'title'       => "Faça Login"
        ]);
    }

    public static function setLoginPage($request)
    {
        $postVars = $request->getPostVars();

        $username = $postVars['username'] ?? '';
        $password = $postVars['password'] ?? '';

        $obUser = User::getUserByEmail($username);

        if(!$obUser instanceof User){
            $request->getRouter()->redirect('/auth/login?status=invalid');
        }

        if(!password_verify($password,$obUser->password)){
            $request->getRouter()->redirect('/auth/login?status=invalid');
        }

        if ($obUser->status == 0) {
            $request->getRouter()->redirect('/auth/login?status=disabled');
        }

        SessionLogin::login($obUser);

        $request->getRouter()->redirect('/');
    }

    public static function setLogout($request)
    {
        SessionLogin::logout();

        $request->getRouter()->redirect('/auth/login');
    }

}