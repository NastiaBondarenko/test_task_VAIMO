# Test task to Vaimo

**Hello!) This is my solution to the PHP internship test at Vaimo.**

I implemented an ATM using **a state machine pattern**. The ATM has the following statuses: no card, password waiting, incorrect password, menu, get money, get credit money, not enough money error.

The ATM has buttons, in this implementation they are implemented in Interface class by entering the numbers 1, 2, or 3. The ATM also accepts digital input. It can be a card number, a password or the desired amount of money. Each state reacts to the corresponding input in its own way and, if necessary, switches the ATM to another state.

The ATM interacts with cards, the array of which is transferred during its initialization. Cards are implemented through the Card class.

In addition to the obvious, the following functions were provided: the card was blocked if the password was entered incorrectly three times in a row, checking whether there are enough bills in the ATM and a fee for withdrawing credit funds.
