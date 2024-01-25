<?php

namespace View\Components\Menu;

require_once 'code/view/components/IComponent.php';
require_once 'code/view/components/tags/pair/containers/ListTag.php';

use View\Components\IComponent;
use View\Components\Tags\Pair\Containers\ListTag;

class BaseMenuItem implements IComponent
{
    public IComponent $component;

    private ListTag $list;

    protected function __construct(
        IComponent $component,
        array $attributes = [],
        array $items = [],
        array $componentsAttributes = []
    ) {
        $this->component = $component;
        $this->list = new ListTag($attributes, $items, $componentsAttributes);
    }

    public function render(): void
    {
        $this->component->render();
        $this->list->render();
    }
}
