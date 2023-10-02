<?php 

namespace App\Helper;

use App\View\View;

class Alert{

    public static function getSuccess($message)
    {
        return View::render('/app/alert',[
            'message' => '<script> alertify.success("'.$message.'");</script>'
        ]);
    }

    public static function getInfo($message)
    {
        return View::render('/app/alert',[
            'message' => '<script> alertify.message("'.$message.'");</script>'
        ]);
    }

    public static function getError($message)
    {
        return View::render('/app/alert',[
            'message' => '<script> alertify.error("'.$message.'");</script>'
        ]);
    }

}