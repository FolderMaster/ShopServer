<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/breadcrumb/BreadcrumbItem.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/contents/TextComponent.php';

use View\Components\Breadcrumb\BreadcrumbItem;
use View\Components\Contents\TextComponent;

$info = null;
switch ($scriptName) {
    case null:
        $info = new BreadcrumbItem(new TextComponent('Аккаунт'));
        break;
    case 'personal-cabinet.php':
        $info = new BreadcrumbItem(
            new TextComponent('Личный кабинет'),
            '/account/personal-cabinet.php'
        );
        break;
    case 'favorites.php':
        $info = new BreadcrumbItem(
            new TextComponent('Избранное'),
            '/account/favorites.php'
        );
        break;
    case 'cart.php':
        $info = new BreadcrumbItem(
            new TextComponent('Корзина'),
            '/account/cart.php'
        );
        break;
    case 'order-delivery.php':
        $info = new BreadcrumbItem(new TextComponent('Доставка заказа'));
        break;
    case 'order-payment.php':
        $info = new BreadcrumbItem(new TextComponent('Оплата заказа'));
        break;
    case 'order.php':
        $info = new BreadcrumbItem(new TextComponent('Заказ'));
        break;
    case 'orders.php':
        $info = new BreadcrumbItem(
            new TextComponent('Заказы'),
            '/account/orders.php'
        );
        break;
    case 'payment-methods.php':
        $info = new BreadcrumbItem(
            new TextComponent('Методы оплаты'),
            '/account/payment-methods.php'
        );
        break;
}
