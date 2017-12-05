<?php
function sanitize($input)
{
    return strip_tags($input);
}

function hash_password($password, $salt = null)
{
    if ($configuration = parse_ini_file("password.ini")) {
        if ($salt == null) {
            $salt = bin2hex(openssl_random_pseudo_bytes($configuration['salt_length']));
        }
        $hash = hash_pbkdf2($configuration['algorithm'], $password, $salt, $configuration['iterations'], $configuration['length']);
        return array(
            'salt' => $salt,
            'hash' => $hash
        );
    } else die("Invalid Password Configuration");
}

/**
 * @param $input array
 * @param $filter array filters
 *
 * If an optional or required value is not provided the field is presumed to be required
 * @return bool true if array valid
 */
function filter_input_array_required_valid(array $input, array $filter)
{
    foreach ($input as $key => $value) {
        if ($value == false && !is_null($value) && is_bool($value)) return false;
        if (!isset($filter[$key]['optional']) || !$filter[$key]['optional']) {
            if ($value == null) {
                return false;
            }
        }
    }

    return true;
}

function get_input_errors(array $input, array $filter)
{
    $errors = array();
    foreach ($input as $key => $value) {
        if ($value == false && !is_null($value) && is_bool($value)) {
            $errors[$key] = "Variable is not valid for this field!";
        } elseif ((!isset($filter[$key]['optional']) || !$filter[$key]['optional']) && $value == null) {
            $errors[$key] = "Variable is empty!";
        } else {
            $errors[$key] = $value;
        }
    }
    return $errors;
}

function logged_in()
{
    return isset($_SESSION['user']);
}