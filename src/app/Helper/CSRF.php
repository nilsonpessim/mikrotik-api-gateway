<?php 

namespace App\Helper;

use App\View\View;

class CSRF{
    
    public static function generateToken()
    {
        $_SESSION['user']['token'] = bin2hex(random_bytes(32));

        return View::render('app/csrf',[
            'token' => $_SESSION['user']['token']
        ]);
    }

    public static function verifyToken($token)
    {
        return (!isset($_SESSION['user']['token']) || $token != $_SESSION['user']['token']) ? false : true;
        unset($_SESSION['user']['token']);
    }
}