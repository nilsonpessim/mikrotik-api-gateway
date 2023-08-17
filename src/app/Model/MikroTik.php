<?php 

namespace App\Model;

use App\Database\Database;

class MikroTik{

    public $id;
    public $host;
    public $fullname;
    public $username;
    public $password;
    public $code;
    public $certificate;
    public $status;

    public function cadastrar()
    {
        $this->id = (new Database('mikrotik'))->insert([
            'host'        => $this->host,
            'fullname'    => $this->fullname,
            'username'    => $this->username,
            'password'    => $this->password,
            'code'        => $this->code,
            'certificate' => $this->certificate,
            'status'      => $this->status
        ]);

        return true;
    }

    public function atualizar()
    {
        return (new Database('mikrotik'))->update('id = '.$this->id,[
            'host' => $this->host,
            'fullname' => $this->fullname,
            'username' => $this->username,
            'password' => $this->password,
            'code'     => $this->code,
            'status'   => $this->status
        ]);
    }

    public function atualizarStatus()
    {
        return (new Database('mikrotik'))->update('id = '.$this->id,[
            'status' => $this->status
        ]);
    }

    public function excluir()
    {
        return (new Database('mikrotik'))->delete('id = '.$this->id);
    }

    public static function getMikroTikById($id)
    {
        return self::getMikroTik('m.id = '.$id)->fetchObject(self::class);
    }
    
    public static function getMikroTikByCode($code)
    {
        return self::getMikroTik('m.code = "'.$code.'"')->fetchObject(self::class);
    }

    public static function getMikroTikByHost($host)
    {
        return self::getMikroTik('m.host = "'.$host.'"')->fetchObject(self::class);
    }

    public static function getMikroTikByFullname($fullname)
    {
        return self::getMikroTik('m.fullname = "'.$fullname.'"')->fetchObject(self::class);
    }

    public static function getMikroTik($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('mikrotik m'))->select($where,$order,$limit,
            "m.id,
            m.host,
            m.fullname,
            m.username,
            m.password,
            m.code,
            m.certificate,
            m.status",
            ""
        );
    }
}