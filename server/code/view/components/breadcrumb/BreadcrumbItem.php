<?php

namespace View\Components\Breadcrumb;

require_once 'code/view/components/IComponent.php';
require_once 'code/view/components/tags/pair/elements/LinkTag.php';
require_once 'code/view/components/tags/pair/elements/SpanTag.php';

use View\Components\IComponent;
use View\Components\Tags\Pair\Elements\LinkTag;
use View\Components\Tags\Pair\Elements\SpanTag;

class BreadcrumbItem implements IComponent
{
    private IComponent $component;

    private ?string $link = null;

    public function __construct(
        IComponent $component,
        ?string $link = null
    ) {
        $this->component = $component;
        $this->link = $link;
    }

    public function removeLink(): void
    {
        $this->link = '';
    }

    public function render(): void
    {
        $component = $this->link !== null ?
            ($this->link == '' ? new LinkTag($this->component) :
                new LinkTag($this->component, $this->link)) :
            new SpanTag($this->component);
        $component->render();
    }
}
