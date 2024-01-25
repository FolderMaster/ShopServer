<?php

namespace Control;

require_once 'code/model/users/UserManager.php';
require_once 'code/view/components/breadcrumb/Breadcrumb.php';
require_once 'code/view/components/menu/Menu.php';
require_once 'code/view/components/menu/LinkMenuItem.php';
require_once 'code/view/components/menu/ButtonMenuItem.php';
require_once 'code/view/components/menu/TitleMenuItem.php';
require_once 'code/view/components/contents/ImageComponent.php';
require_once 'code/view/components/contents/TextComponent.php';

use \Exception;
use Model\Users\UserManager;
use View\Components\Breadcrumb\Breadcrumb;
use View\Components\Menu\Menu;
use View\Components\Menu\LinkMenuItem;
use View\Components\Menu\ButtonMenuItem;
use View\Components\Menu\TitleMenuItem;
use View\Components\Contents\ImageComponent;
use View\Components\Contents\TextComponent;

function Authorize(): void
{
    session_start();

    $email = null;
    $password = null;
    if (isset($_SESSION['Login'])) {
        $email = $_SESSION['Login']['Email'];
        $password =  $_SESSION['Login']['Password'];
    } else if (isset($_COOKIE['Login'])) {
        $email = $_COOKIE['Login']['Email'];
        $password =  $_COOKIE['Login']['Password'];
    }

    if (isset($email, $password)) {
        $userId = UserManager::getUserId($email, $password);
        if (isset($userId)) {
            global $pageData;
            $pageData['User'] = $userId;
        }
    }
}

function GetBreadcrumb(string $scriptPath): Breadcrumb
{
    $pathParts = explode('/', $scriptPath);
    $scriptName = array_pop($pathParts);
    $currentPath = '';
    $items = [];
    while (count($pathParts) > 0) {
        $currentDictionary = array_shift($pathParts);
        if (strlen($currentDictionary) > 0) {
            $currentPath .= $currentDictionary . '/';
        }
        $item = GetInfo($currentPath);
        if ($item) {
            $items[] = $item;
        }
    }
    if ($scriptName != 'index.php') {
        $item = GetInfo($currentPath, $scriptName);
        if ($item) {
            $items[] = $item;
        }
    }
    return new Breadcrumb($items);
}

function GetInfo(string $path, ?string $scriptName = null): mixed
{
    $infoPath = $path . 'info.php';
    if (file_exists($infoPath)) {
        include $infoPath;
        return $info;
    } else {
        throw new Exception('Not find info script!');
    }
}

function GetMainMenu(): Menu
{
    return new Menu([
        new LinkMenuItem(new ImageComponent(
            '/resources/images/logo.png',
            ['class' => 'logo']
        ), '/'),
        new LinkMenuItem(new TextComponent('Магазин'), '/shop/', [], [], [
            new LinkMenuItem(
                new TextComponent('Электроника'),
                '/shop/section.php?1',
                [],
                [],
                [
                    new LinkMenuItem(
                        new TextComponent('Периферия'),
                        '/shop/section.php?2',
                        [],
                        [],
                        [
                            new LinkMenuItem(
                                new TextComponent('Наушники'),
                                '/shop/section.php?3'
                            ),
                            new LinkMenuItem(
                                new TextComponent('Клавиатуры'),
                                '/shop/section.php?4'
                            ),
                            new LinkMenuItem(
                                new TextComponent('Компьютерные мыши'),
                                '/shop/section.php?5'
                            )
                        ]
                    ),
                ]
            ),
        ])
    ]);
}

function GetMenuForGuest(): Menu
{
    return new Menu([
        new ButtonMenuItem(
            new TextComponent('Регистрация'),
            'createRegistrationForm();'
        ),
        new ButtonMenuItem(
            new TextComponent('Авторизация'),
            'createLoginForm();'
        ),
    ]);
}

function GetMenuForUser(): Menu
{
    return new Menu([
        new TitleMenuItem(new TextComponent('Аккаунт'), [], [], [
            new LinkMenuItem(
                new TextComponent('Личный кабинет'),
                '/account/personal-cabinet.php'
            ),
            new LinkMenuItem(
                new TextComponent('Методы оплаты'),
                '/account/payment-methods.php'
            ),
            new LinkMenuItem(
                new TextComponent('Избранное'),
                '/account/favorites.php'
            ),
            new LinkMenuItem(
                new TextComponent('Корзина'),
                '/account/cart.php'
            ),
            new LinkMenuItem(
                new TextComponent('Заказы'),
                '/account/orders.php'
            ),
            new ButtonMenuItem(
                new TextComponent('Выход'),
                '',
                'get',
                '/ajax/logout.php',
                'location.reload()'
            )
        ])
    ]);
}
