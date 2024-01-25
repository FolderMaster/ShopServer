<?php

namespace View\Components\Tags\Pair\Elements;

require_once 'code/view/components/tags/pair/elements/BaseElementTag.php';

use View\Components\IComponent;

class SpanTag extends BaseElementTag
{
    public function __construct(IComponent $component, array $attributes = [])
    {
        parent::__construct('span', $attributes, $component);
    }
}
