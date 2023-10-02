<?php 

namespace App\Helper;

class ipAddress{

    public $ipv4Allow;
    public $ipv6Allow;
    public $ipUser;

    public function __construct($obUser)
    {
        $this->ipv4Allow = $obUser->ipv4;
        $this->ipv6Allow = $obUser->ipv6;
        $this->ipUser    = $_SERVER['REMOTE_ADDR'];

        if (filter_var($this->ipUser, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            
            return self::checkIPv4();
        
        } elseif (filter_var($this->ipUser, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            
            return self::checkIPv6();
        }
    }

    private function checkIPv4()
    {
        $ipLong = ip2long($this->ipUser);

        $allow = false;

        foreach (explode(',', $this->ipv4Allow) as $network) {

            $network = trim($network);

            $xpd = explode('/', $network);
            $xpd[1] = (!isset($xpd[1])) ? '32' : $xpd[1];

            list($subnet, $mask) = $xpd;
    
            $subnetLong = ip2long($subnet);
            $maskLong = ~((1 << (32 - $mask)) - 1);
    
            if (($ipLong & $maskLong) === ($subnetLong & $maskLong)) {
                return true;
            }
        }

        $allow = ($allow == false) ? ($network[0] == "*") ?? true : false;

        if(!$allow) {
            throw new \Exception("Unable to login via your IP address: $this->ipUser", 403);
        }
    }

    private function checkIPv6()
    {
        $ipBin = inet_pton($this->ipUser);

        $allow = false;

        foreach (explode(',', $this->ipv6Allow) as $network) {

            $network = trim($network);

            $xpd = explode('/', $network);
            $xpd[1] = (!isset($xpd[1])) ? '128' : $xpd[1];
            
            list($subnet, $prefix) = $xpd;

            $subnetBin = inet_pton($subnet);
    
            $prefixBytes = (int)($prefix / 8);
            $ipPrefix = substr($ipBin, 0, $prefixBytes);
            $subnetPrefix = substr($subnetBin, 0, $prefixBytes);
    
            if ($ipPrefix === $subnetPrefix) {
                $allow = true;
            }
        }

        if ($allow == false) {
            $allow = ($network[0] == "*") ?? true;
        }

        if(!$allow) {
            throw new \Exception("Unable to login via your IP address: $this->ipUser", 403);
        }
    
    }
}