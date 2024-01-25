<?php

require_once 'code/control/Ajax.php';

use function Control\SendResponse;

session_start();
if (!isset($_SESSION['Login']) && !isset($_COOKIE['Login'])) {
    SendResponse('Отмена выхода из авторизации: авторизации нет!', 400);
}
unset($_SESSION['Login']);
setcookie('Login[Email]', '', -1, '/');
setcookie('Login[Password]', '', -1, '/');
SendResponse('Выход из авторизации!', 200);
