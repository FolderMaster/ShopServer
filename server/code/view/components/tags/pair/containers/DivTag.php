<?php

namespace View\Components\Tags\Pair\Containers;

require_once 'code/view/components/tags/pair/containers/BaseContainerTag.php';

class DivTag extends BaseContainerTag
{
    public function __construct(
        array $attributes = [],
        array $components = []
    ) {
        parent::__construct('div', $attributes, $components);
    }
}
