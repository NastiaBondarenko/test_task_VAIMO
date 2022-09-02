<?php namespace cardClass;
use Exception;

class Card{
    private $card_number;
    private $pin_code;
    private $owner_name;
    private $balance;
    private $credit_limit;
    private $debt;
    private $blocked;
    private $number_of_incorrect_passwords;
    private $password_attempts;

    public function __construct($card_number, $pin_code, $owner_name, $balance, $credit_limit) {
        $this->card_number = $card_number;
        $this->pin_code = $pin_code;
        $this->owner_name = $owner_name;
        $this->credit_limit = $credit_limit;
        $this->balance = $balance;
        $this->blocked = false;
        $this->number_of_incorrect_passwords = 0;
        $this->debt = 0;
        $this->password_attempts = 3;
    }

    public function withdraw_money_from_balance($amount){
        if($amount <= $this-> balance){
            $this-> balance -= $amount;
            return $amount;
        }
        else throw new Exception("Недостатньо коштів \n");
    }

    public function get_credit_money($amount, $commission){
        $amount -= $this-> balance;
        $this-> balance = 0;
        $this->debt = $amount *(1 + $commission);
        $this->credit_limit -= $amount;
        return $amount;
    }


    public function wrong_password(){
        if($this->number_of_incorrect_passwords == $this->password_attempts)  $this->blocked = true;
        else $this->number_of_incorrect_passwords ++;
    }
    
    public function correct_password(){
        $this->number_of_incorrect_passwords = 0;
    }

    public function get_balance(){
        return $this->balance;
    }

    public function get_credit_limit(){
        return $this->credit_limit;
    }

    public function get_balance_with_credit(){
        return $this->credit_limit+$this->balance;
    }
    
    public function get_pin_code(){
        return $this-> pin_code;
    }

    public function get_card_number(){
        return $this-> card_number;
    }
    
    public function is_blocked(){
        return $this->blocked;
    }

    public function check_pin_code($input){
        return $this-> pin_code == $input;
    }

}

?>

