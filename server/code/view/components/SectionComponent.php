<?php

namespace View\Components;

require_once 'code/view/components/IComponent.php';
require_once 'code/model/items/Section.php';

use Model\Items\Section;

class SectionComponent implements IComponent
{
    private Section $section;

    private bool $isShort;

    function __construct(
        Section $section,
        bool $isShort = false
    ) {
        $this->section = $section;
        $this->isShort = $isShort;
    }

    public function render(): void
    {
        if ($this->isShort) { ?>
            <div class="item item-header highlighted">
                <a class="item-link" href="<?= $this->section->getUrl() ?>"><?= $this->section->getName() ?></a>
            </div>
        <?php } else { ?>
            <div class="item">
                <div class="item-header highlighted">
                    <a class="item-link" href="<?= $this->section->getUrl() ?>"><?= $this->section->getName() ?></a>
                </div>
                <div class="item-content block table-layout center-layout">
                    <div>
                        <img class="icon big-icon icon-background" src="<?= $this->section->getImage()->getSource() ?>" />
                    </div>
                    <div>
                        <p class="text-align-center item-text"><?= $this->section->getDescription() ?></p>
                    </div>
                </div>
            </div>
<?php  }
    }
}
