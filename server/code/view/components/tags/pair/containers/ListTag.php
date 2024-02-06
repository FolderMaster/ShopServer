<?php

namespace View\Components\Tags\Pair\Containers;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/containers/BaseSpecialContainerTag.php';

class ListTag extends BaseSpecialContainerTag
{
    public function __construct(
        array $attributes = [],
        array $components = [],
        array $componentsAttributes = [],

    ) {
        parent::__construct('ul', $attributes, $components, 'li', $componentsAttributes);
    }
}
