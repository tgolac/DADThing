<?php
$db = null;

const ERROR_SUCCESS = 1;
const ERROR_FIELDS = -1;
const ERROR_SYSTEM = -2;
const ERROR_LOGIN = -3;
const ERROR_ALERT = -4;

error_reporting(E_ALL | E_STRICT);
function getDB()
{
    global $db;
    if ($db == null) return connect();
    return $db;
}

function connect()
{
    global $db;
    if ($configuration = parse_ini_file("config.ini")) {
        $db = new PDO(
            $configuration['type'] . ':host=' . $configuration['host'] . ';dbname=' . $configuration['name'] . ';charset=utf8mb4',
            $configuration['user'],
            $configuration['pass']
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $db;
    } else die("Invalid MySQL Configuration");
}