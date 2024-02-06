<?php

namespace View\Components\Menu;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/IComponent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/elements/ListItemTag.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/containers/DivTag.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/containers/UlTag.php';

use View\Components\IComponent;
use View\Components\Tags\Pair\Elements\ListItemTag;

class Menu implements IComponent
{
    private array $components;

    public function __construct(array $components)
    {
        $this->components = $components;
    }

    public function getComponents(): array
    {
        return $this->components;
    }

    public function setComponents(array $value): void
    {
        $this->components = $value;
    }

    public function render(): void
    {
        $items = [];
        foreach ($this->components as $component) {
            (new ListItemTag($component, []))->render();
        }
    }
};
