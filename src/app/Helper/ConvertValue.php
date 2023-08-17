<?php 

namespace App\Helper;

class ConvertValue{

    private $date;
    private $price;
    private $dot;
    private $string;

    public function __construct($value)
    {
        $this->date   = $value;
        $this->price  = $value;
        $this->dot    = $value;
        $this->string = $value;
    }

    public function setDateTime($format = 'd/M/Y H:i:s'){
        if (!empty($this->date)) {
            return (new \DateTime($this->date))->format($format);
        }
    }
    
    public function setDateTimeDBO($format = 'Y-m-d H:i:s'){
        if (!empty($this->date)) {
            $this->date = str_replace('/', '-', $this->date);
            return (new \DateTime($this->date))->format($format);
        }
    }

    public function setPrice(){
        return number_format($this->price,2,",",".");
    }

    public function getDot(){
        return str_replace(",", ".", $this->dot);
    }

    public function setString(int $type = 1){
        $pattern     = ["/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"];
        $replacement = explode(" ","a A e E i I o O u U n N c C");
        
        //TEXTO EM MAIUSCULO
        if ($type == 1) {
            $string = strtoupper(preg_replace($pattern, $replacement ,$this->string));
        }

        //TEXTO EM MINUSCULO - SEM ESPACAMENTO
        if ($type == 99) {
            $string = strtolower(preg_replace($pattern, $replacement ,$this->string));
            $string = preg_replace('/[ -]+/' , '_' , $string);
        }

        return $string;
    }

    public function getDiffDate(){

        $dt1 = strtotime($this->date);
        $dt2 = time();

        return $dt2 - $dt1;
    }
}