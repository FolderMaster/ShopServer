<?php

namespace View\Components\Tags\Pair\Elements;

require_once 'code/view/components/tags/pair/elements/BaseElementTag.php';

use View\Components\IComponent;

class ButtonTag extends BaseElementTag
{
    public function __construct(
        IComponent $component,
        string $script = '',
        string $method = '',
        string $action = '',
        string $successCode = '',
        array $attributes = []
    ) {
        if ($script !== '') {
            $attributes['onclick'] = $script;
        }
        if ($method !== '') {
            $attributes['method'] = $method;
        }
        if ($action !== '') {
            $attributes['action'] = $action;
        }
        if ($successCode !== '') {
            $attributes['onsuccess'] = $successCode;
        }
        parent::__construct('button', $attributes, $component);
    }
}
