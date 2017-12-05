<?php
include "mysql/connect.php";
include_once "modules/functional/utils.php";
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hello, world!</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styleGroup.css">
    <link href="queries.css" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
            integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
            crossorigin="anonymous"></script>
    <script src="js/api.js"></script>
    <script src="js/forms.js"></script>
    <script src="aboutUs.js"></script>
</head>
<body>
<?php
include 'ui/pages/header.php';
if (isset($_GET['page'])) {
    switch ($_GET['page']) {
        case "login":
            include 'ui/pages/login.html';
            break;
        case "register":
            include 'ui/pages/register.html';
            break;
        case "news":
            include 'ui/pages/news.html';
            break;
        case "post":
            if(isset($_GET['id'])) {
                include 'ui/pages/post.php';
            } else {
                include 'ui/pages/404.html';
            }
            break;
        case "about":
            include 'ui/pages/hero.html';
            include 'ui/pages/aboutUs.html';
            break;
        case "logout":
            if (isset($_SESSION['user'])) {
                unset($_SESSION['user']);
            }
            header("Location: " . $_SERVER['PHP_SELF'] . "?page=login");
    }
} else {
    include 'ui/pages/hero.html';
    include 'ui/pages/aboutUs.html';
}
?>
<!--<div id="post"></div>-->
<!--<script type="text/javascript">-->
<!--    generatePost($('#post'), 1);-->
<!--</script>-->

</body>
</html>
