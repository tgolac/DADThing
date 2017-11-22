<?php
function sanitize($input) {
    return strip_tags($input);
}

function hash_password($password) {
    if ($configuration = parse_ini_file("password.ini")) {
        $salt = bin2hex(openssl_random_pseudo_bytes($configuration['salt_length']));
        $hash = hash_pbkdf2($configuration['algorithm'], $password, $salt, $configuration['iterations'], $configuration['length']);
        return array(
            'salt' => $salt,
            'hash' => $hash
        );
    } else die("Invalid Password Configuration");
}
?>