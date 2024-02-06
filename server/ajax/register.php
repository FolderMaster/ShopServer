<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Ajax.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/users/UserManager.php';

use Model\Users\UserManager;
use function Control\SendResponse;

if (!isset(
    $_POST['name'],
    $_POST['email'],
    $_FILES['avatar'],
    $_POST['birthDate'],
    $_POST['phoneNumber'],
    $_POST['password'],
    $_POST['repeatPassword']
)) {
    SendResponse('Регистрация отменена: нет необходимых полей!', 400);
}
$fullName = strip_tags($_POST['name']);
$email = strip_tags($_POST['email']);
$phoneNumber = $_POST['phoneNumber'];
$birthDate = new DateTime($_POST['birthDate']);
$password = $_POST['password'];
$repeatPassword = $_POST['repeatPassword'];
if ($password != $repeatPassword) {
    SendResponse('Регистрация отменена: пароли не совпадают!', 400);
}
if (UserManager::checkUser($email)) {
    SendResponse('Регистрация отменена: пользователь занят!', 400);
}
$avatar = $_FILES['avatar'];
if ($avatar['error'] !== UPLOAD_ERR_OK) {
    SendResponse('Регистрация отменена: ошибка загрузки файла!', 400);
}
$loadPath = $avatar['tmp_name'];
$fileName = uniqid() . '.' . pathinfo($avatar['name'], PATHINFO_EXTENSION);
$savePath = 'uploads/' . $fileName;
if (!move_uploaded_file($loadPath, $savePath)) {
    SendResponse('Регистрация отменена: ошибка загрузки файла на сервере!', 500);
}
$fileSource = '/' . $savePath;
if (UserManager::createUser(
    $fullName,
    $email,
    password_hash($password, PASSWORD_DEFAULT),
    $phoneNumber,
    $birthDate,
    $fileSource,
    $fileName,
    file_get_contents($savePath)
)) {
    SendResponse('Регистрация прошла успешно!', 200);
} else {
    SendResponse('Регистрация прошла с ошибкой!', 400);
}
