<?php 

namespace App\Controller\Page;

use App\View\View;

class Page{

    public static function getNavbar()
    {
        return View::render('/app/navbar',[
            'fullname' => USER_INFO_FULLNAME,
            'username' => USER_INFO_USERNAME,
        ]);
    }

    public static function getPage($title, $content)
    {
        return View::render('/app/page',[
            'description' => APP_DESCRIPTION,
            'author'      => APP_AUTHOR,
            'title'       => $title,
            'navbar'      => self::getNavbar(),
            'content'     => $content,
        ]);
    }

}