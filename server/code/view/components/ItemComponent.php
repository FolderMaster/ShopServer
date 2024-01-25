<?php

namespace View\Components;

require_once 'code/model/items/Item.php';
require_once 'code/view/components/IComponent.php';

use Model\Items\Item;

class ItemComponent implements IComponent
{
    private Item $item;

    private float $price;

    private int $count;

    private int $cartCount;

    private bool $isInFavorites;

    private bool $withButtons;

    function __construct(
        Item $item,
        float $price,
        int $count,
        int $cartCount,
        bool $isInFavorites,
        bool $withButtons = true
    ) {
        $this->item = $item;
        $this->price = $price;
        $this->count = $count;
        $this->cartCount = $cartCount;
        $this->isInFavorites = $isInFavorites;
        $this->withButtons = $withButtons;
    }

    public function render(): void
    {
        $characteristicParts = [];
        foreach ($this->item->getCharacteristics() as $characteristic) {
            $characteristicParts[] = $characteristic->getName() . ' - ' .
                $characteristic->getValue() . ' ' .
                $characteristic->getUnit()->getSymbol();
        }
        $characteristicText = join(', ', $characteristicParts);
?>
        <div class="item" item-id="<?= $this->item->getId() ?>">
            <div class="item-header highlighted">
                <a class="item-link" href="<?= $this->item->getUrl() ?>"><?= $this->item->getName() ?></a>
            </div>
            <div class="item-content block table-layout">
                <div>
                    <img src="<?= $this->item->getImages()[0]->getSource() ?>" class="icon medium-icon icon-background" />
                </div>
                <div>
                    <p class="item-text">Цена:
                        <span><?= $this->price ?></span>
                    </p>
                    <p class="item-text">Количество:
                        <span class="count"><?= $this->count ?></span>
                    </p>
                    <p class="item-text">Описание: <?= $this->item->getDescription() ?> </p>
                    <?= ($characteristicText != '' ? '<p class="item-text">' . $characteristicText . '</p>' : '') ?>
                </div>
                <?php if ($this->withButtons) { ?>
                    <div class="item-buttons table-layout">
                        <?php if ($this->cartCount > 0) { ?>
                            <div class="block list-layout count-block form">
                                <div class="table-layout">
                                    <button class="interactive remove-button">-</button>
                                    <input name="count" type="number" value="<?= $this->cartCount ?>" min="0" max="<?= $this->count ?>" />
                                    <button class="interactive add-button">+</button>
                                </div>
                                <input name="count" type="range" value="<?= $this->cartCount ?>" min="0" max="<?= $this->count ?>" />
                            </div>
                        <?php } else { ?>
                            <button class="interactive item-header cart-button">В корзину</button>
                        <?php } ?>
                        <button class="interactive item-header favorites-button<?= ($this->isInFavorites ? ' active' : '') ?>">
                            <?= ($this->isInFavorites ? 'Удалить из избранного' : 'В избранное') ?> </button>
                    </div>
                <?php } ?>
            </div>
        </div>
<?php }
}
