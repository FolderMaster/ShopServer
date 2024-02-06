<?php

namespace View\Components\Contents;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/tags/single/BaseSingleTag.php';

use View\Components\Tags\Single\BaseSingleTag;

class ImageComponent extends BaseSingleTag
{
    public function __construct(string $source, array $attributes = [])
    {
        $attributes['src'] = $source;
        parent::__construct('img', $attributes);
    }

    public function getSource(): string
    {
        return $this->attributes['src'];
    }

    public function setSource(string $value): void
    {
        $this->attributes['src'] = $value;
    }
}
