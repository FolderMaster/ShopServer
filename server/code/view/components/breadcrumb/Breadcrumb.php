<?php

namespace View\Components\Breadcrumb;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/IComponent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/containers/ListTag.php';

use View\Components\IComponent;
use View\Components\Tags\Pair\Containers\ListTag;

class Breadcrumb implements IComponent
{
    private ListTag $list;

    public function __construct(
        array $items = []
    ) {
        $this->list = new ListTag(['class' => 'breadcrumb'], $items);
    }

    public function addItems(array $items): void
    {
        foreach ($items as $item) {
            $this->list->addComponent($item);
        }
    }

    public function render(): void
    {
        $items = $this->list->getComponents();
        $count = count($items);
        if ($count > 0) {
            $items[$count - 1]->removeLink();
            $this->list->setComponents($items);
        }
        $this->list->render();
    }
}
