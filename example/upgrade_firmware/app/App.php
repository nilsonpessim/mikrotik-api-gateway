<?php 

namespace App;

use GuzzleHttp\{Client, RequestOptions};

class App{

    public $username;
    public $password;
 
    public function __construct()
    {
        $this->username = "user";
        $this->password = "pass";
    }

    // function set identity
    public function setIdentity($urlBase, $id, $name)
    {
        self::post("$urlBase/mikrotik/$id/system/identity/set", ["name" => "TechLabs - $name"]);
    }

    // function change DNS
    public function changeDNS($urlBase, $id)
    {
        //check dns
        $dns = self::get("$urlBase/mikrotik/$id/ip/dns")->result;
    
        //update dns
        if (!$dns->servers) {
            self::post("$urlBase/mikrotik/$id/ip/dns/set", ["servers" => "8.8.8.8,8.8.4.4"]);
        }
    }

    // function upgrade version
    public function upgradeVersion($urlBase, $id)
    {
        //check version
        $update = self::get("$urlBase/mikrotik/$id/system/package/update")->result;

        //update version
        if ($update->{'installed-version'} < "7.11.2") {
            self::post("$urlBase/mikrotik/$id/system/package/update/install", '{""}', RequestOptions::BODY);
        }
    }

    // function method get
    public function get($URL)
    {
        $auth = new Client(['auth' => [$this->username, $this->password], 'verify' => false]);
        $response = $auth->request("GET", $URL);
    
        // return
        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody());
        }
    }

    // function method post
    public function post($URL, $DATA, $REQUEST = RequestOptions::JSON)
    {
        $auth = new Client(['auth' => [$this->username, $this->password], 'verify' => false]);
        $response = $auth->request("POST", $URL, [$REQUEST => $DATA]);

        // return
        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody());
        }
    }

}