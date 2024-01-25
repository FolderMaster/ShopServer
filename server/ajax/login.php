<?php

require_once 'code/control/Ajax.php';
require_once 'code/model/users/UserManager.php';

use Model\Users\UserManager;
use function Control\SendResponse;

session_start();
if (isset($_SESSION['Login']) || isset($_COOKIE['Login'])) {
    SendResponse('Авторизации отменена: авторизация уже есть!', 400);
}
if (!isset($_GET['email'], $_GET['password'])) {
    SendResponse('Авторизация отменена: нет необходимых полей!', 404);
}
$email = strip_tags($_GET['email']);
$password = $_GET['password'];
if (UserManager::getUserId($email, $password) == null) {
    SendResponse('Авторизация отменена: неверный пароль/логин!', 400);
}
$_SESSION['Login'] = ['Email' => $email, 'Password' => $password];
setcookie('Login[Email]', $email, time() + 3600, '/');
setcookie('Login[Password]', $password, time() + 3600, '/');
SendResponse('Авторизация прошла успешно!', 200);
