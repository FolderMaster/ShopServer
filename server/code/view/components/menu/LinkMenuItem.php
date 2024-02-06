<?php

namespace View\Components\Menu;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/menu/BaseMenuItem.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/elements/LinkTag.php';

use View\Components\IComponent;
use View\Components\Tags\Pair\Elements\LinkTag;

class LinkMenuItem extends BaseMenuItem
{
    function __construct(
        IComponent $component,
        string $link,
        array $linkAttributes = [],
        array $attributes = [],
        array $items = [],
        array $componentsAttributes = []
    ) {
        if ($link === $_SERVER['REQUEST_URI']) {
            $link = '';
        }
        parent::__construct(new LinkTag(
            $component,
            $link,
            $linkAttributes
        ), $attributes, $items, $componentsAttributes);
    }
}
