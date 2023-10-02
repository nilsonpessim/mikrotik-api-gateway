<?php 

namespace App\Session;

class Login{

    private static function init()
    {
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    public static function login($obUser)
    {
        self::init();

        $_SESSION['user']['information'] = [
            'id'       => $obUser->id,
            'fullname' => $obUser->fullname,
            'username' => $obUser->username
        ];

        return true;
    }

    public static function isLogged()
    {
        self::init();

        return isset($_SESSION['user']['information']['id']);
    }

    public static function logout()
    {
        self::init();

        unset($_SESSION['user']);

        return true;
    }

    public static function getStatus()
    {
        self::init();
    }

}