<?php

require_once 'code/view/components/breadcrumb/BreadcrumbItem.php';
require_once 'code/view/components/contents/ImageComponent.php';

use View\Components\Breadcrumb\BreadcrumbItem;
use View\Components\Contents\ImageComponent;

$info = null;
switch ($scriptName) {
    case null:
        $info = new BreadcrumbItem(
            new ImageComponent(
                '/resources/images/home.png',
                ['class' => 'tiny-icon']
            ),
            '/'
        );
        break;
}
