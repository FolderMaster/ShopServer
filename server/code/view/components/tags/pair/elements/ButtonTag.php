<?php

namespace View\Components\Tags\Pair\Elements;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/pair/elements/BaseElementTag.php';

use View\Components\IComponent;

class ButtonTag extends BaseElementTag
{
    public function __construct(
        IComponent $component,
        string $script = '',
        string $method = '',
        string $action = '',
        string $successCode = '',
        bool $ajax = true,
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
        if ($ajax) {
            $attributes['ajax'] = 'true';
        }
        parent::__construct('button', $attributes, $component);
    }
}
