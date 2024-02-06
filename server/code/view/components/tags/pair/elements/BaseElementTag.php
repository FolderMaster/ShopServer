<?php

namespace View\Components\Tags\Pair\Elements;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/IComponent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/BasePairTag.php';

use View\Components\IComponent;
use View\Components\Tags\Pair\BasePairTagCreator;

class BaseElementTag extends BasePairTagCreator implements IComponent
{
    protected IComponent $component;

    public function __construct(
        string $tag,
        array $attributes = [],
        IComponent $component = null
    ) {
        parent::__construct($tag, $attributes);
        $this->component = $component;
    }

    public function setComponent(IComponent $component): void
    {
        $this->component = $component;
    }

    public function getComponent(): IComponent
    {
        return $this->component;
    }

    public function render(): void
    {
        $this->createPairTag($this->tag, $this->attributes, [$this->component, 'render']);
    }
};
