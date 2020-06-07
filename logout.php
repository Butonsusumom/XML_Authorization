<?php
session_start();
session_destroy();
setcookie('login', null, time() - 1);
setcookie('key', null, time() - 1);
header("Location: index.php");