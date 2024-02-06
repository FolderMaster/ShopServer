<?php

namespace View\Components\Tags\Single;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/IComponent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/BaseTag.php';

use View\Components\IComponent;
use View\Components\Tags\BaseTag;

class BaseSingleTag extends BaseTag implements IComponent
{
    public function __construct(
        string $tag,
        array $attributes = [],
    ) {
        parent::__construct($tag, $attributes);
    }

    public function createSingleTag(string $tag, array $attributes): void
    {
        echo '<' . $tag . ' ' . $this->createAttributes($attributes) . '/>';
    }

    public function render(): void
    {
        echo $this->createSingleTag($this->tag, $this->attributes);
    }
};
