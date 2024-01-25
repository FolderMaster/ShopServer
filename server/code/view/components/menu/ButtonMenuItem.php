<?php

namespace View\Components\Menu;

require_once 'code/view/components/menu/BaseMenuItem.php';
require_once 'code/view/components/tags/pair/elements/ButtonTag.php';

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
            $buttonAttributes
        ), $attributes, $items, $componentsAttributes);
    }
}
