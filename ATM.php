<?php namespace atm;
require_once 'Card.php';
use cardClass\Card ;
require_once 'States.php';
use statesClasses\NoBankCard ;
use Exception;

class ATM{
    private $amount_of_cash;
    private $card;
    private $cards;
    private $state;
    private $withdrawal_amount;
    private $commission_percentage;

    public function __construct($amount_of_cash, $cards) {
        $this->amount_of_cash = $amount_of_cash;
        $this->cards = $cards;
        $this->state = new NoBankCard($this);
        $this->commission_percentage = 0.03;
    }

    public function set_withdrawal_amount($withdrawal_amount){
        $this -> withdrawal_amount = $withdrawal_amount;
    }

    public function changeState($state){
        $this->state = $state;
    }

    public function search_card($card_number){
        foreach ($this->cards as $card){
            if($card->get_card_number() == $card_number){
                return $card;
            }
        }
       throw new Exception("Карти з даним номером не існує в базі даних \n");
    }

    public function set_card($card){
        $this->card = $card;
    }

    public function is_blocked(){
        return $this->card->is_blocked();
    }

    public function is_enough_in_ATM($amount){
        return $amount <= $this->amount_of_cash;
    }

    public function is_enough_with_credit_balance($amount){
        return  $amount <= $this->card->get_balance_with_credit($amount);  
    }

    public function withdraw_cash_from_balance($amount){
        return $this->card->withdraw_money_from_balance($amount);
    }

    public function get_balance(){
        return $this->card->get_balance();
    }

    public function get_credit_money(){
        $this->card->get_credit_money($this->withdrawal_amount,  $this->commission_percentage);
        return $this->withdrawal_amount;
    }

    public function onclick_button_1(){
        $this->state->onclick_button_1();
    }

    public function onclick_button_2(){
        $this->state->onclick_button_2();
    }

    public function onclick_button_3(){
        $this->state->onclick_button_3();
    }

    public function onInput($input){
        $this->state->onInput($input);
    }
    
    public function check_pin_code($input){
        return $this->card->check_pin_code($input);
    }

    public function correct_password(){
        $this->card->correct_password();
    }

    public function wrong_password(){
        $this->card->wrong_password();
    }
    
}


class InterfaceATM{

    private $ATM;

    public function __construct($ATM) {
        $this->ATM = $ATM;
    }

    public function work(){
        echo "Введіть карту: ";
        while(true){
            try{
            $input = readline();
            switch($input){
                case 1:
                    $this->ATM->onclick_button_1();
                    break;
                case 2:
                    $this->ATM->onclick_button_2();
                    break;
                case 3:
                    $this->ATM->onclick_button_3();
                    break;
                default:
                    try{
                        $num_input = intval($input);
                        $this->ATM->onInput($input);
                    }
                    catch(Exeption $e){
                        echo "Error. Щось пішло не так. Спробуйте, ще раз \n";
                    }
                } 
            }
            catch (Exeption $e){
                echo $e->getMessage() ;
            }
        }
    }
}

$card1 = new Card(123456789, 1234, "Анастасія Бондаренко", 300000, 0);
$card2 = new Card(456789123, 4321, "Тарас Шевченко", 38768, 20000);
$card3 = new Card(789123456, 9876, "Пес Патрон", 12, 10);
$ATM = new ATM(100000,  array($card1, $card2, $card3));
$interface = new InterfaceATM($ATM);
$interface -> work();

?>
