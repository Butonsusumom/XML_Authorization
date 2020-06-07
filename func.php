<?php

function get_user($text)
{
    $null_object = (object)array('name' => 'null', 'login' => 'null', 'password' => 'null', 'email' => 'null', 'key' => 'null');
    if (file_exists('db/base.xml')) {
        $users = simplexml_load_file('db/base.xml');
        foreach ($users as $value) {
            if ($value->login == $text || $value->email == $text) {
                return (object)$value;
            }
        }
        return $null_object;
    } else {
        return $null_object;
    }
}

//0 - the user is added to data base succesfully
//1 - error data base open
//2 - such email already exists
//3 - such login already exists
function add_user($name, $login, $password, $email, $key)
{
    if (get_user($login)->login != $login) {
        if (get_user($email)->email != $email) {
            if (file_exists('db/base.xml')) {
                $users_str = file_get_contents('db/base.xml');

                $sxmle = new SimpleXMLElement($users_str);
                $user = $sxmle->addChild('user');
                $user->addChild('name', $name);
                $user->addChild('login', $login);
                $user->addChild('password', password_hash($password, PASSWORD_DEFAULT));
                $user->addChild('email', $email);
                $user->addChild('key', $key);

                $sxmle->asXML('db/base.xml');
                return 0;
            } else {
                return 1;
            }
        } else {
            return 2;
        }
    } else {
        return 3;
    }
}
//0 - everything is ok
//1 - no such user
//2 - open data base error
function set_key_to_user($login, $key)
{
    if (file_exists('db/base.xml')) {
        $users_str = file_get_contents('db/base.xml');
        $sxmle = new SimpleXMLElement($users_str);
        $issetkey = false;
        foreach ($sxmle as $value) {
            if ($value->login == $login) {
                $value->key = $key;
                $issetkey = true;
            }
        }
        $sxmle->asXML('db/base.xml');
        if ($issetkey) {
            return 0;
        } else {
            return 1;
        }
    } else {
        return 2;
    }
}

function get_key_to_user($login)//getting the user key
{
    if (file_exists('db/base.xml')) {
        $users = simplexml_load_file('db/base.xml');
        foreach ($users as $value) {
            if ($value->login == $login) {
                return (string)$value->key;
            }
        }
    }
}

function get_key() //reterns siquens
{
    $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $result = '';
    for ($i = 0; $i < 30; $i++) {
        $result = substr_replace($result, substr($string, random_int(0, 61), 1), $i);
    }
    return $result;
}
