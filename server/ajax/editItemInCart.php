<?php

require_once 'code/control/Ajax.php';
require_once 'code/model/users/UserManager.php';
require_once 'code/model/users/CartManager.php';

use Model\Users\UserManager;
use Model\Users\CartManager;
use function Control\SendResponse;

session_start();
if (!isset($_GET['itemId'], $_GET['count'])) {
    SendResponse('Изменение товара в корзине отменено: 
    нет необходимых полей!', 400);
}
$itemId = $_GET['itemId'];
$count = $_GET['count'];
if (!isset($_SESSION['Login'])) {
    SendResponse('Изменение товара в корзине отменено: авторизации нет!', 400);
}
$userId = UserManager::getUserId(
    $_SESSION['Login']['Email'],
    $_SESSION['Login']['Password']
);
if (!isset($userId)) {
    SendResponse('Изменение товара в корзине отменено: 
    авторизация не пройдена!', 400);
}
if ($count < 0) {
    SendResponse('Изменение товара в корзине отменено: 
    неверное значение параметра!', 403);
} else if ($count == 0) {
    if (CartManager::removeItemSet($userId, $itemId)) {
        SendResponse('Удаление из корзины товара прошло успешно!', 200);
    } else {
        SendResponse('Удаление из корзины товара отменено: 
        внутреняя ошибка сервера!', 500);
    }
} else {
    if (CartManager::checkItemSet($userId, $itemId)) {
        if (CartManager::changeItemSet($userId, $itemId, $count)) {
            SendResponse(
                'Изменение количества товара в корзине товара прошло успешно!',
                200
            );
        } else {
            SendResponse('Изменение количества товара в корзине отменено: 
            внутреняя ошибка сервера!', 500);
        }
    } else {
        if (CartManager::addItemSet($userId, $itemId, $count)) {
            SendResponse('Добавление в корзину товара прошло успешно!', 200);
        } else {
            SendResponse('Добавление в корзину товара отменено: 
            внутреняя ошибка сервера!', 500);
        }
    }
}
