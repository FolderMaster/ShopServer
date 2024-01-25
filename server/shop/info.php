<?php

require_once 'code/view/components/breadcrumb/BreadcrumbItem.php';
require_once 'code/view/components/contents/TextComponent.php';

use View\Components\Breadcrumb\BreadcrumbItem;
use View\Components\Contents\TextComponent;

$info = null;
switch ($scriptName) {
    case null:
        $info = new BreadcrumbItem(new TextComponent('Магазин'), '/shop/');
        break;
}
