<?php

namespace View\Components\Tags\Pair\Elements;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/elements/BaseElementTag.php';

use View\Components\IComponent;

class LinkTag extends BaseElementTag
{
    public function __construct(IComponent $component, ?string $link = null, array $attributes = [])
    {
        if ($link !== null) {
            $attributes['href'] = $link;
        }
        parent::__construct('a', $attributes, $component);
    }
}
