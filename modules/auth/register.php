<?php
require_once 'auth.php';

$filters = array(
    'username' => FILTER_SANITIZE_STRING,
    'email' => FILTER_VALIDATE_EMAIL,
    'password' => array(
        'filter' => FILTER_SANITIZE_STRING,
        'options' => array('min_range' => 6, 'max_range' => 24)
    ),
    'first_name' => FILTER_SANITIZE_STRING,
    'last_name' => FILTER_SANITIZE_STRING
);
if ($input = filter_input_array(INPUT_POST, $filters)) {
    if(filter_input_array_required_valid($input, $filters)) {
        $stmt = getDB()->prepare("SELECT * FROM users WHERE username=:username");
        $stmt->bindParam(':username', $input['username']);
        $stmt->execute() or die(print_r($stmt->errorInfo(), true));
        if (!$stmt->rowCount()) {
            $stmt = getDB()->prepare("SELECT * FROM users WHERE email=:email");
            $stmt->bindParam(':email', $input['email']);
            $stmt->execute() or die(print_r($stmt->errorInfo(), true));
            if (!$stmt->rowCount()) {
                $stmt = getDB()->prepare("INSERT INTO users (username, email, password, salt, first_name, last_name, registration_date, active) VALUES (:username, :email, :password, :salt, :first_name, :last_name, NOW(), 'AWAITING_EMAIL_CONFIRMATION')");

                $password_data = hash_password($input['password']);
                $stmt->bindParam(':username', $input['username']);
                $stmt->bindParam(':email', $input['email']);
                $stmt->bindParam(':password', $password_data['hash']);
                $stmt->bindParam(':salt', $password_data['salt']);
                $stmt->bindParam(':first_name', $input['first_name']);
                $stmt->bindParam(':last_name', $input['last_name']);


                $to = $input['email'];
                $subject = 'Activate your account';
                $message = "<a href='http://serenity.ist.rit.edu/~group5800/modules/activate.php?token="
                    . md5($input['email']) . "'>Activate your account!</a>";
                $headers = 'From: group5800@serenity.ist.rit.edu' . "\r\n" .
                    'Reply-To: rxa6313@gmail.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                echo($to);
                if (mail($to, $subject, $message, $headers)) {
                    $stmt->execute() or die(print_r($stmt->errorInfo(), true));
                    echo("User successfully registered!");
                } else {
                    die("Error while sending email!");
                }
            } else {
                die("Email exists");
            }
        } else {
            die("Username exists");
        }
    } else {
        die(json_encode($input));
    }
}
