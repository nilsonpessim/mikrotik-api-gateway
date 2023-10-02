<?php 

namespace App\Controller\Page;

use App\View\View;

class Home extends Page{

    public static function getHome($request)
    {
        $content = View::render('/home/home',[
            "description" => APP_DESCRIPTION,
            "author"      => APP_AUTHOR,
            "version"     => APP_VERSION,
            "external"    => self::externalPage()
        ]);

        return parent::getPage('MikroTik API Gateway', $content);
    }

    public static function externalPage()
    {     
        $file = @file_get_contents("https://raw.githubusercontent.com/nilsonpessim/mikrotik-api-gateway/main/home.php");

        return ($file === false) ? "" : $file;
    }
}