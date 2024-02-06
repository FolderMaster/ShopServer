<?php

namespace View\Components\Menu;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/menu/BaseMenuItem.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/elements/SpanTag.php';

use View\Components\IComponent;
use View\Components\Tags\Pair\Elements\SpanTag;

class TitleMenuItem extends BaseMenuItem
{
    function __construct(
        IComponent $component,
        array $titleAttributes = [],
        array $attributes = [],
        array $items = [],
        array $componentsAttributes = []
    ) {
        parent::__construct(new SpanTag(
            $component,
            $titleAttributes
        ), $attributes, $items, $componentsAttributes);
    }
}
