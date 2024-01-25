<?php

namespace View\Components\Tags\Pair\Containers;

require_once 'code/view/components/tags/pair/containers/BaseContainerTag.php';

use View\Components\IComponent;

class BaseSpecialContainerTag extends BaseContainerTag implements IComponent
{
    protected array | string $componentsTags;
    protected array $componentsAttributes;

    public function __construct(
        string $tag,
        array $attributes = [],
        array $components = [],
        array | string $componentsTags = [],
        array $componentsAttributes = []
    ) {
        parent::__construct($tag, $attributes, $components);
        $this->componentsTags = $componentsTags;
        $this->componentsAttributes = $componentsAttributes;
    }

    public function getComponentsTags(): array
    {
        return $this->componentsTags;
    }

    public function setComponentsTags(array $value): void
    {
        $this->componentsTags = $value;
    }

    public function getComponentsAttributes(): array
    {
        return $this->componentsAttributes;
    }

    public function setComponentsAttributes(array $value): void
    {
        $this->componentsAttributes = $value;
    }

    protected function getComponentTag(int $index): string
    {
        if (gettype($this->componentsTags) === 'array') {
            return $this->componentsTags[$index];
        } else {
            return $this->componentsTags;
        }
    }

    protected function getComponentAttributes(int $index): array
    {
        if (isset($this->componentsAttributes[$index])) {
            return $this->componentsAttributes[$index];
        } else {
            return $this->componentsAttributes;
        }
    }

    public function renderComponents(): void
    {
        foreach ($this->components as $key => $component) {
            $this->createPairTag($this->getComponentTag($key), $this->getComponentAttributes($key), [$component, 'render']);
        }
    }

    public function render(): void
    {
        $this->createPairTag($this->tag, $this->attributes, [$this, 'renderComponents']);
    }
};
