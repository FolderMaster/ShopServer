<?php

namespace View\Components\Tags\Pair\Containers;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/IComponent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/BasePairTag.php';

use View\Components\IComponent;
use View\Components\Tags\Pair\BasePairTagCreator;

class BaseContainerTag extends BasePairTagCreator implements IComponent
{
    protected array $components;

    public function __construct(
        string $tag,
        array $attributes = [],
        array $components = []
    ) {
        parent::__construct($tag, $attributes);
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

    public function addComponent(IComponent $component): void
    {
        $this->components[] = $component;
    }

    public function renderComponents(): void
    {
        foreach ($this->components as $component) {
            $component->render();
        }
    }

    public function render(): void
    {
        $this->createPairTag($this->tag, $this->attributes, [$this, 'renderComponents']);
    }
};
