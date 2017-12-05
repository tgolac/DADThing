<?php
session_start();
if (isset($_SESSION['user'])) {
    if (isset($_GET['logout'])) {
        $_SESSION['user'] = null;
    } else {
        var_dump($_SESSION['user']);
    }
}
