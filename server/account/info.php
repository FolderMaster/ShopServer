<?php

require_once 'code/view/components/breadcrumb/BreadcrumbItem.php';
require_once 'code/view/components/contents/TextComponent.php';

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
