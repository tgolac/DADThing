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
    if (filter_input_array_required_valid($input, $filters)) {
        $stmt = getDB()->prepare("SELECT * FROM users WHERE username=:username");
        $stmt->bindParam('username', $input['username']);
        $stmt->execute() or die(print_r($stmt->errorInfo(), true));
        if ($stmt->rowCount()) {
            if ($data = $stmt->fetch()) {
                if ($data['password'] == hash_password($input['password'], $data['salt'])['hash']) {
                    switch ($data['active']) {
                        case "ACTIVE":
                            $_SESSION['user'] = array(
                                'id' => $data['id'],
                                'username' => $data['username'],
                                'email' => $data['email'],
                                'first_name' => $data['first_name'],
                                'last_name' => $data['last_name'],
                                'fullname' => $data['first_name'] . ' ' . $data['last_name']
                            );
                            die(json_encode(array(
                                "status" => ERROR_SUCCESS
                            )));
                        case "BANNED":
                            die(json_encode(array(
                                "status" => ERROR_ALERT,
                                "alert" => "User is banned, contact administrator!"
                            )));
                        case "INACTIVE":
                            die(json_encode(array(
                                "status" => ERROR_ALERT,
                                "alert" => "Please check your email for the confirmation link!"
                            )));
                    }
                } else {
                    die(json_encode(array(
                        "status" => ERROR_ALERT,
                        "alert" => "Incorrect login data!"
                    )));
                }
            } else {
                die(json_encode(array(
                    "status" => ERROR_SYSTEM
                )));
            }
        } else {
            die(json_encode(array(
                "status" => ERROR_ALERT,
                "alert" => "Incorrect login data!"
            )));
        }
    } else {
        die(json_encode(array(
                "status" => ERROR_FIELDS,
                "errors" => get_input_errors($input, $filters))
        ));
    }
} else {
    die(json_encode(array(
        "status" => ERROR_SYSTEM
    )));
}