<?php

namespace View\Components\Tags;

class BaseTag
{
    protected string $tag;
    protected array $attributes;

    public function __construct(string $tag, array $attributes = [])
    {
        $this->tag = $tag;
        $this->attributes = $attributes;
    }

    public function createAttributes(array $attributes): string
    {
        $result = '';
        foreach ($attributes as $key => $value) {
            $result = $result . $key . '="' . $value . '" ';
        }
        return $result;
    }
};
