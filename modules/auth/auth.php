<?php
require_once '../../mysql/connect.php';
require_once '../functional/utils.php';

session_start();

function requires_login()
{
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        header('Location: http://serenity.ist.rit.edu/~group5800/index.php?page=login');
        return false;
    }
}