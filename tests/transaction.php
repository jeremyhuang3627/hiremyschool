<?php
require_once "../braintree-php-2.20.0/lib/Braintree.php";

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('gf5rtm7crxqpp2w7');
Braintree_Configuration::publicKey('hbcvx6j79n4ccrz5');
Braintree_Configuration::privateKey('453a42c6f21fb696a1b8eae51c025bd3');

$result = Braintree_Transaction::sale(array(
    "amount" => "1000.00",
    "creditCard" => array(
        "number" => $_POST["number"],
        "cvv" => $_POST["cvv"],
        "expirationMonth" => $_POST["month"],
        "expirationYear" => $_POST["year"]
    ),
    "options" => array(
        "submitForSettlement" => true
    )
));

if ($result->success) {
    echo("Success! Transaction ID: " . $result->transaction->id);
} else if ($result->transaction) {
    echo("Error: " . $result->message);
    echo("<br/>");
    echo("Code: " . $result->transaction->processorResponseCode);
} else {
    echo("Validation errors:<br/>");
    foreach (($result->errors->deepAll()) as $error) {
        echo("- " . $error->message . "<br/>");
    }
}
?>