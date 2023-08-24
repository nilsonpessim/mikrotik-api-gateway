<?php 

namespace App\Model;

use App\Database\Database;

class Api{

    public $id;
    public $fullname;
    public $username;
    public $password;
    public $mikrotik;
    public $ipv4;
    public $ipv6;
    public $status;

    public function cadastrar()
    {
        $this->id = (new Database('api'))->insert([
            'fullname' => $this->fullname,
            'username' => $this->username,
            'password' => $this->password,
            'mikrotik' => $this->mikrotik,
            'ipv4'     => $this->ipv4,
            'ipv6'     => $this->ipv6,
            'status'   => $this->status
        ]);

        return true;
    }

    public function atualizar()
    {
        return (new Database('api'))->update('id = '.$this->id,[
            'fullname' => $this->fullname,
            'ipv4'     => $this->ipv4,
            'ipv6'     => $this->ipv6,
        ]);
    }


    public function atualizarStatus()
    {
        return (new Database('api'))->update('id = '.$this->id,[
            'status' => $this->status
        ]);
    }

    public function atualizarMikroTik()
    {
        return (new Database('api'))->update('id = '.$this->id,[
            'mikrotik' => $this->mikrotik
        ]);
    }

    public function excluir()
    {
        return (new Database('api'))->delete('id = '.$this->id);
    }

    public static function getUserById($id)
    {
        return self::getUsers('u.id = '.$id)->fetchObject(self::class);
    }

    public static function getUserByUsername($username)
    {
        return self::getUsers('u.username = "'.$username.'" AND u.status = 1')->fetchObject(self::class);
    }

    public static function getUserByFullname($fullname)
    {
        return self::getUsers('u.fullname = "'.$fullname.'"')->fetchObject(self::class);
    }

    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('api u'))->select($where,$order,$limit,
            "u.id,
            u.fullname,
            u.username,
            u.password,
            u.mikrotik,
            u.ipv4,
            u.ipv6,
            u.status",
            ""
        );
    }
}