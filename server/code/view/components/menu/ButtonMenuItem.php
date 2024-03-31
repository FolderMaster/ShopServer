<?php

namespace View\Components\Menu;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/menu/BaseMenuItem.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/elements/ButtonTag.php';

use View\Components\IComponent;
use View\Components\Tags\Pair\Elements\ButtonTag;

class ButtonMenuItem extends BaseMenuItem
{
    function __construct(
        IComponent $component,
        string $script = '',
        string $method = '',
        string $action = '',
        string $successCode = '',
        bool $ajax = true,
        array $buttonAttributes = [],
        array $attributes = [],
        array $items = [],
        array $componentsAttributes = []
    ) {
        parent::__construct(new ButtonTag(
            $component,
            $script,
            $method,
            $action,
            $successCode,
            $ajax,
            $buttonAttributes
        ), $attributes, $items, $componentsAttributes);
    }
}
