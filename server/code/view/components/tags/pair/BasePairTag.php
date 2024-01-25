<?php

namespace View\Components\Tags\Pair;

require_once 'code/view/components/tags/BaseTag.php';

use View\Components\Tags\BaseTag;

class BasePairTagCreator extends BaseTag
{
    public function __construct(
        string $tag,
        array $attributes = [],
    ) {
        parent::__construct($tag, $attributes);
    }

    public function createPairTag(string $tag, array $attributes, ?callable $render): void
    {
        echo '<' . $tag . ' ' . $this->createAttributes($attributes) . '>';
        if ($render) {
            $render();
        }
        echo '</' . $tag . '>';
    }
};
