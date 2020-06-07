<?php


session_start();
if (isset($_SESSION['user_session']) && !isset($_POST['ajax'])) {
    header("Location: logged.php");
}
include_once('func.php');
if (!isset($_SESSION['user_session']) && isset($_COOKIE['key'])) {
    $cookieuser = get_user($_COOKIE['login']);
    if ($cookieuser->key == $_COOKIE['key']) {
        $_SESSION['user_session'] = (array)$cookieuser;
        $key = get_key();
        set_key_to_user((string)$cookieuser->login, $key);
        setcookie('login', (string)$cookieuser->login, time() + 60 * 60 * 24 * 30);
        setcookie('key', $key, time() + 60 * 60 * 24 * 30);
        header("Location: index.php");
    }
}

//0 - successfully authorization
//1 - invalid login or password
if (isset($_POST['ajax'])) {
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);
    $saveme = htmlspecialchars($_POST['saveme']);
    if (strlen($login) < 2 && strlen($password) < 8) {
        echo json_encode(array('login' => false, 'code' => 1));
        exit;
    }
    $user = get_user(htmlspecialchars($_POST['login']));
    if ($user->login == $login) {
        if (password_verify($password, $user->password)) {
            $_SESSION['user_session'] = (array)$user;
            if ($saveme == 1) {
                $key = get_key();
                set_key_to_user((string)$user->login, $key);
                setcookie('login', (string)$user->login, time() + 60 * 60 * 24 * 30);
                setcookie('key', $key, time() + 60 * 60 * 24 * 30);
            }
            echo json_encode(array('login' => true, 'code' => 0));
        } else {
            echo json_encode(array('login' => false, 'code' => 2));
        }
    } else {
        echo json_encode(array('login' => false, 'code' => 2));
    }
    exit;
}
include_once('pages/index.html');
