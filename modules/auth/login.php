<?php
require_once 'auth.php';

$filters = array(
    'username' => FILTER_SANITIZE_STRING,
    'password' => array(
        'filter' => FILTER_SANITIZE_STRING,
        'options' => array('min_range' => 6, 'max_range' => 24)
    )
);

if ($input = filter_input_array(INPUT_POST, $filters)) {
    $stmt = getDB()->prepare("SELECT * FROM users WHERE username=:username AND active='ACTIVE'");
    $stmt->bindParam('username', $input['username']);
    $stmt->execute() or die(print_r($stmt->errorInfo(), true));
    if ($stmt->rowCount()) {
        if ($data = $stmt->fetch()) {
            if ($data['password'] == hash_password($input['password'], $data['salt'])['hash']) {
                $_SESSION['user'] = array(
                    'id' => $data['id'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name']
                );
                echo("Successfully logged in!");
            } else {
                die("Username/password is incorrect!");
            }
        } else {
            die(print_r($stmt->errorInfo(), true));
        }
    } else {
        die("User does not exist!");
    }
}