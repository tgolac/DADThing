<?php
require_once('auth.php');
$filters = array(
    'token' => FILTER_SANITIZE_STRING
);
if ($input = filter_input_array(INPUT_GET, $filters)) {
    $stmt = getDB()->prepare("SELECT * FROM users WHERE MD5(email)=:token");
    $stmt->bindParam(':token', $input['token']);
    $stmt->execute() or die(print_r($stmt->errorInfo(), true));
    if ($stmt->rowCount() == 1) {
        $stmt = getDB()->prepare("UPDATE users SET active='ACTIVE' WHERE MD5(email)=:token");
        $stmt->bindParam(':token', $input['token']);
        $stmt->execute() or die(print_r($stmt->errorInfo(), true));
        echo("User successfully activated!");
    } else {
        die("Account does not exist!");
    }
} else {
    die("Input data not correctly formatted");
}