<?php
require_once '../mysql/connect.php';
require_once 'functional/utils.php';

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
if (empty($_POST)) { ?>
    Empty
<?php } else {
    if ($input = filter_input_array(INPUT_POST, $filters)) {
        $stmt = getDB()->prepare("SELECT * FROM users WHERE username=:username");
        $stmt->bindParam(':username', $input['username']);
        if ($stmt->execute() && !$stmt->rowCount()) {
            $stmt = getDB()->prepare("SELECT * FROM users WHERE email=:email");
            $stmt->bindParam(':email', $input['email']);
            if ($stmt->execute() && !$stmt->rowCount()) {
                $stmt = getDB()->prepare("INSERT INTO users (username, email, password, salt, first_name, last_name, registration_date, active) VALUES (:username, :email, :password, :salt, :first_name, :last_name, NOW(), 'AWAITING_EMAIL_CONFIRMATION')");

                $password_data = hash_password($input['password']);
                $stmt->bindParam(':username', $input['username']);
                $stmt->bindParam(':email', $input['email']);
                $stmt->bindParam(':password', $password_data['hash']);
                $stmt->bindParam(':salt', $password_data['salt']);
                $stmt->bindParam(':first_name', $input['first_name']);
                $stmt->bindParam(':last_name', $input['last_name']);
                if ($stmt->execute()) {

                    die("User successfully registered!");
                } else {
                    die("User not registered!");
                }
            } else {
                die("Email exists");
            }
        } else {
            die("Username exists");
        }
    }
}
