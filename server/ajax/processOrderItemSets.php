<?php

require_once 'code/control/Ajax.php';
require_once 'code/model/users/UserManager.php';
require_once 'code/model/orders/Order.php';

use Model\Users\UserManager;
use Model\Orders\Order;
use function Control\SendResponse;

session_start();
if (!isset($_GET['cost'], $_GET['count'], $_GET['totalCost'])) {
    SendResponse('Оформление заказа отменено: нет необходимых полей!', 400);
}
$cost = $_GET['cost'];
$count = $_GET['count'];
$totalCost = $_GET['totalCost'];
if (!isset($_SESSION['Login'])) {
    SendResponse('Оформление заказа отменено: авторизации нет!', 400);
}
$userId = UserManager::GetUserId(
    $_SESSION['Login']['Email'],
    $_SESSION['Login']['Password']
);
if (!isset($userId)) {
    SendResponse('Оформление заказа отменено: авторизация не пройдена!', 400);
}
if (Order::createOrder($userId)) {
    SendResponse('Оформление заказа прошло успешно!', 200);
} else {
    SendResponse('Оформление заказа отменено: внутренняя ошибка сервера!', 400);
}
