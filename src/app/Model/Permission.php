<?php 

namespace App\Model\User;

use App\Database\Database;

class Permission{

    public $id;
    public $id_user;
    public $generator;
    public $generator_model;
    public $generator_local;
    public $generator_monitoring;
    public $generator_supply;
    public $generator_maintenance;
    public $user;
    public $car;
    public $car_model;
    public $car_supply;
    public $car_maintenance;
    public $voip;
    public $event;

    public function cadastrar()
    {
        $this->id = (new Database('user_perm'))->insert([
            'id_user'               => $this->id_user,
            'generator'             => $this->generator,
            'generator_model'       => $this->generator_model,
            'generator_local'       => $this->generator_local,
            'generator_monitoring'  => $this->generator_monitoring,
            'generator_supply'      => $this->generator_supply,
            'generator_maintenance' => $this->generator_maintenance,
            'user'                  => $this->user,
            'car'                   => $this->car,
            'car_model'             => $this->car_model,
            'car_supply'            => $this->car_supply,
            'car_maintenance'       => $this->car_maintenance,
            'voip'                  => $this->voip,
            'event'                 => $this->event
        ]);

        return true;
    }

    public function atualizar()
    {
        return (new Database('user_perm'))->update('id = '.$this->id,[
            'user'                  => $this->user,
            'car'                   => $this->car,
            'car_model'             => $this->car_model,
            'car_supply'            => $this->car_supply,
            'car_maintenance'       => $this->car_maintenance,
            'voip'                  => $this->voip,
            'event'                 => $this->event,
            'generator'             => $this->generator,
            'generator_model'       => $this->generator_model,
            'generator_local'       => $this->generator_local,
            'generator_monitoring'  => $this->generator_monitoring,
            'generator_supply'      => $this->generator_supply,
            'generator_maintenance' => $this->generator_maintenance,
        ]);
    }

    public function excluir()
    {
        return (new Database('user_perm'))->delete('id = '.$this->id);
    }

    public static function getPermissionByUser($id_user)
    {
        return self::getPermission('id_user = '.$id_user)->fetchObject(self::class);
    }

    public static function getPermission($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('user_perm'))->select($where,$order,$limit,$fields,"");
    }
}