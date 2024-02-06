<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Ajax.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/users/UserManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/users/FavoritesManager.php';

use Model\Users\UserManager;
use Model\Users\FavoritesManager;
use function Control\SendResponse;

session_start();
if (!isset($_GET['itemId'], $_GET['operationType'])) {
    SendResponse('Изменение товара в избранном отменено: 
    нет необходимых полей!', 400);
}
$itemId = $_GET['itemId'];
$operationType = boolval($_GET['operationType']);
if (!isset($_SESSION['Login'])) {
    SendResponse('Изменение товара в избранном отменено: авторизации нет!', 400);
}
$userId = UserManager::getUserId(
    $_SESSION['Login']['Email'],
    $_SESSION['Login']['Password']
);
if (!isset($userId)) {
    SendResponse('Изменение товара в избранном отменено: 
    авторизация не пройдена!', 400);
}
if ($operationType) {
    if (FavoritesManager::addItem($userId, $itemId)) {
        SendResponse('Добавление в избранное товара прошло успешно!', 200);
    } else {
        SendResponse('Добавление в избранное товара отменено: 
        внутреняя ошибка сервера!', 500);
    }
} else {
    if (FavoritesManager::removeItem($userId, $itemId)) {
        SendResponse('Удаление из избранного товара прошло успешно!', 200);
    } else {
        SendResponse('Удаление из избранного товара отменено: 
        внутреняя ошибка сервера!', 500);
    }
}
