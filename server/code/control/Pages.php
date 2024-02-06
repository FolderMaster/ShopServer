<?php

namespace Control;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/users/UserManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/breadcrumb/Breadcrumb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/menu/Menu.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/menu/LinkMenuItem.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/menu/ButtonMenuItem.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/menu/TitleMenuItem.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/contents/ImageComponent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/contents/TextComponent.php';

use \Exception;
use Model\Users\UserManager;
use View\Components\Breadcrumb\Breadcrumb;
use View\Components\Menu\Menu;
use View\Components\Menu\LinkMenuItem;
use View\Components\Menu\ButtonMenuItem;
use View\Components\Menu\TitleMenuItem;
use View\Components\Contents\ImageComponent;
use View\Components\Contents\TextComponent;

function GetMainUrl(): string
{
    return sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        $_SERVER['SERVER_PORT'] ? ':' . $_SERVER['SERVER_PORT'] : ''
    );
}

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
            $pageData['UserId'] = $userId;
        }
    }
}

function ShowError(
    ?string $title = null,
    ?string $descrition = null,
    ?int $statusCode = null
): void {
    $errorData['Title'] = $title;
    $errorData['Description'] = $descrition;
    $errorData['StatusCode'] = $statusCode;
    require_once $_SERVER['DOCUMENT_ROOT'] . '/error.php';
    die;
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
    $infoPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $path . 'info.php';
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
                '/shop/section.php?id=1',
                [],
                [],
                [
                    new LinkMenuItem(
                        new TextComponent('Периферия'),
                        '/shop/section.php?id=2',
                        [],
                        [],
                        [
                            new LinkMenuItem(
                                new TextComponent('Наушники'),
                                '/shop/section.php?id=3'
                            ),
                            new LinkMenuItem(
                                new TextComponent('Клавиатуры'),
                                '/shop/section.php?id=4'
                            ),
                            new LinkMenuItem(
                                new TextComponent('Компьютерные мыши'),
                                '/shop/section.php?id=5'
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
