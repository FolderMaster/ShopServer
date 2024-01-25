<?php

require_once 'code/control/Pages.php';

use function Control\Authorize;

Authorize();
$pageData['Title'] = 'Главная';
require_once 'code/view/includes/header.php';
?>
<p class="header3">
    Веб-сайт интернет-магазина, в котором продаётся бесчисленное количество товаров
    различных категорий.
</p>
<p class="header2">Контакты:</p>
<dl class="header3">
    <dt>Телефон:
    <dd>+54973979345
    <dt>Адрес:
    <dd>улица Пушкина, дом Колотушкино
</dl>
<?php require_once 'code/view/includes/footer.php' ?>