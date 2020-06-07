<?php


session_start();
if (isset($_SESSION['user_session'])) {
    header("Location: index.php");
}
include_once('func.php');

//0 - success register
//1 -error data base open
//2 - such email already exists
//3 -such login already exists
if (isset($_POST['ajax'])) {
    $name = htmlspecialchars($_POST['name']);
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $email = htmlspecialchars($_POST['email']);
    if ($password == $confirm_password) {
        if (strlen($password) < 8 && strlen($confirm_password) < 8 && strlen($email) < 5) {
            echo json_encode(array('login' => false, 'code' => 1));
            exit;
        }
        switch (add_user($name, $login, $password, $email, "null")) {
            case 0:
                $user = get_user($login);
                $_SESSION['user_session'] = (array)$user;
                echo json_encode(array('login' => true, 'code' => 0));
                break;
            case 1:
                echo json_encode(array('login' => false, 'code' => 1));
                break;
            case 2:
                echo json_encode(array('login' => false, 'code' => 2));
                break;
            case 3:
                echo json_encode(array('login' => false, 'code' => 3));
                break;
        }
    } else {
        echo json_encode(array('login' => false, 'code' => 4));
    }
    exit;
}
include_once('pages/reg.html');