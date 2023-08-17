<?php 

namespace App\Model;

use App\Database\Database;

class User{

    public $id;
    public $fullname;
    public $username;
    public $password;
    public $status;

    public function cadastrar()
    {
        $this->id = (new Database('user'))->insert([
            'fullname'      => $this->fullname,
            'username'      => $this->username,
            'password'      => $this->password,
            'status'        => $this->status
        ]);

        return true;
    }

    public function atualizar()
    {
        return (new Database('user'))->update('id = '.$this->id,[
            'fullname' => $this->fullname,
            'username' => $this->username
        ]);
    }
    
    public function atualizarPassword()
    {
        return (new Database('user'))->update('id = '.$this->id,[
            'password' => $this->password
        ]);
    }

    public function atualizarStatus()
    {
        return (new Database('user'))->update('id = '.$this->id,[
            'status' => $this->status
        ]);
    }

    public function excluir()
    {
        return (new Database('user'))->delete('id = '.$this->id);
    }

    public static function getUserById($id)
    {
        return self::getUsers('u.id = '.$id)->fetchObject(self::class);
    }

    public static function getUserByEmail($username)
    {
        return self::getUsers('u.username = "'.$username.'"')->fetchObject(self::class);
    }

    public static function getUserByFullname($fullname)
    {
        return self::getUsers('u.fullname = "'.$fullname.'"')->fetchObject(self::class);
    }

    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('user u'))->select($where,$order,$limit,
            "u.id,
            u.fullname,
            u.username,
            u.password,
            u.status",
            ""
        );
    }
}