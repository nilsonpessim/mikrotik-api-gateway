<?php 

namespace App\Controller\Page;

use App\View\View;

class Error extends Page{

    public static function getError($code)
    {
        $content = View::render('/app/'.$code,[
            'title' => 'Error ' . $code
        ]);

        return parent::getPage('Error ' . $code, $content);
    }
}