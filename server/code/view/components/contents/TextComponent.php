<?php

namespace View\Components\Contents;

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/IComponent.php';

use View\Components\IComponent;

class TextComponent implements IComponent
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function render(): void
    {
        echo $this->text;
    }
}
