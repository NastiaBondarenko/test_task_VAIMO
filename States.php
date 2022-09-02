<?php namespace statesClasses;
use Exception;

abstract class State
{
    protected $ATM;

    public function __construct($ATM) {
        $this->ATM = $ATM;
    }

    public function onclick_button_1(){
        echo("Error. Щось пішло не так\n");
    }
    public function onclick_button_2(){
        echo("Error. Щось пішло не так\n");
    }
    public function onclick_button_3(){
        echo("Error. Щось пішло не так\n");
    }
    public function onInput($input){
        echo("Error. Щось пішло не так\n");
    }

}

class NoBankCard extends State{
    public function onInput($card_number){
        try{
            $card = $this->ATM->search_card($card_number);
            $this->ATM->set_card($card);
            echo "Введіть пін код: ";
            $this->ATM->changeState(new WaitPinCode($this->ATM));
        }
        catch (Exception $e){
            echo $e->getMessage() ;
            echo "Введіть карту: ";
        }  
    }
}

class WaitPinCode extends State{
    public function onInput($input){
        if($this->ATM->is_blocked()) {
            echo ("Карту заблоковано. \n");
            $this->ATM->changeState(new NoBankCard($this->ATM));
        }
        else{
            if($this->ATM->check_pin_code($input)){
                $this->ATM -> correct_password();
                echo "Меню: \n    1-Переглянути баланс;\n    2-Зняти готівку;\n    3-Вийняти карту.\nНомер команди:  ";
                $this->ATM->changeState(new Menu($this->ATM));
            }
            else{
                $this->ATM ->wrong_password();
                echo "Пароль не вірний. \n    1-Спробувати ще раз \n    2-Ввести іншу карту\n";
                $this->ATM->changeState(new WrongPinCode($this->ATM));
            }
        }    
    }
}

class WrongPinCode extends State{
    public function onclick_button_1(){
        $this->ATM->changeState(new WaitPinCode($this->ATM));
    }
    public function onclick_button_2(){
        $this->ATM->changeState(new NoBankCard($this->ATM));
    }
}

class Menu extends State{
    public function onclick_button_1(){
        $balance = $this->ATM->get_balance();
        echo("Ваш баланс $balance \n");
        echo "Меню: \n    1-Переглянути баланс;\n    2-Зняти готівку;\n    3-Вийняти карту.\nНомер команди:  ";
        $this->ATM->changeState(new Menu($this->ATM));
    }
    public function onclick_button_2(){
        echo "Введіть суму, що бажаєте зняти. Сума повинна бути кратна 100: ";
        $this->ATM->changeState(new GetCash($this->ATM));
    }
    public function onclick_button_3(){
        echo "Карту вийнято\n";
        echo "Введіть карту: ";
        $this->ATM->changeState(new NoBankCard($this->ATM));
    }
}


class GetCash extends State{
    public function onInput($input){
        if($input%100 != 0) echo "Введіть суму, що кратна 100 :";
        else{
            if($this->ATM->is_enough_in_ATM($input)){
                try{
                    $this->ATM->withdraw_cash_from_balance($input);
                    echo "Ваші $input гривень\n";
                    echo "Меню: \n    1-Переглянути баланс;\n    2-Зняти готівку;\n    3-Вийняти карту.\nНомер команди:  ";
                    $this->ATM->changeState(new Menu($this->ATM));
                }
                catch (Exception $e){
                    if($this->ATM->is_enough_with_credit_balance($input)){
                        echo "На карті не достатньо грошей, скористатись кредитними коштами? Процент за зняття кредитних коштів - 3%.\n";
                        echo "1-Зняти кредитні кошти;\n2-Ввести іншу суму;\n3-Повернутись до меню;\n";
                        $this->ATM->set_withdrawal_amount($input);
                        $this->ATM->changeState(new GetCreditMoney($this->ATM));
                    }
                    else{
                        echo "Недостатньо коштів на вашій картці. \n";
                        echo "1-Ввести іншу суму;\n2-Повернутись до меню;\n";
                        $this->ATM->changeState(new NotEnoughMoney($this->ATM));
                    }
                }
            }
            else{    
                echo "Нажаль недостатньо коштів у банкоматі. Приносимо вибачення за спричинені незручності.\n";
                echo "1-Ввести іншу суму;\n2-Повернутись до меню;\n";
                $this->ATM->changeState(new NotEnoughMoney($this->ATM));
            }
            
        }
    }
}

class NotEnoughMoney extends State{
    public function onclick_button_1(){
        echo "Введіть суму, що бажаєте зняти. Сума повинна бути кратна 100: ";
        $this->ATM->changeState(new GetCash($this->ATM));
    }
    public function onclick_button_2(){
        echo "Меню: \n    1-Переглянути баланс;\n    2-Зняти готівку;\n    3-Вийняти карту.\nНомер команди:  ";
        $this->ATM->changeState(new Menu($this->ATM));
    } 
}

class GetCreditMoney extends State{
    public function onclick_button_1(){
        $withdrawal_amount = $this->ATM->get_credit_money();
        echo "Ваші $withdrawal_amount гривень\n";
        $this->onclick_button_3();        
    }
    public function onclick_button_2(){
        echo "Введіть суму, що бажаєте зняти. Сума повинна бути кратна 100: ";
        $this->ATM->changeState(new GetCash($this->ATM));
    }
    public function onclick_button_3(){
        echo "Меню: \n    1-Переглянути баланс;\n    2-Зняти готівку;\n    3-Вийняти карту.\nНомер команди:  ";
        $this->ATM->changeState(new Menu($this->ATM));
    }
}

?>
