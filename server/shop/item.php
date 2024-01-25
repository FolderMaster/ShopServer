<?php

require_once 'code/model/items/Item.php';
require_once 'code/model/items/Section.php';
require_once 'code/model/items/ItemPriceManager.php';
require_once 'code/model/items/StoredItemManager.php';
require_once 'code/model/users/CartManager.php';
require_once 'code/model/users/FavoritesManager.php';
require_once 'code/view/components/ItemComponent.php';
require_once 'code/control/Pages.php';

use Model\Items\Item;
use Model\Items\Section;
use Model\Items\StoredItemManager;
use Model\Items\ItemPriceManager;
use Model\Users\FavoritesManager;
use Model\Users\CartManager;
use View\Components\Breadcrumb\BreadcrumbItem;
use View\Components\Contents\TextComponent;
use function Control\Authorize;
use function Control\GetBreadcrumb;

Authorize();
if (count($_GET) != 1) {
    require_once 'error.php';
    die;
}
$queryKey = array_key_first($_GET);
if (!is_numeric($queryKey)) {
    require_once 'error.php';
    die;
}
$itemId = (int)$queryKey;

$item = new Item($itemId);
if (isset($pageData['User'])) {
    $userId = $pageData['User'];
}
$price = ItemPriceManager::getItemPrice($itemId, 1);
$count = StoredItemManager::getCountOfItem($itemId);
$cartCount = 0;
$isInFavorites = false;
if ($userId) {
    $isInFavorites = FavoritesManager::checkItem($userId, $itemId);
    $cartCount = CartManager::getItemCount($userId, $itemId);
}

$breadcrumb = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
$currentSections = [];
$currentSectionId = $item->getSectionId();
while ($currentSectionId !== null) {
    $section = new Section($currentSectionId);
    $currentSections[] = $section;
    $currentSectionId = $section->getParentSectionId();
}
$breadcrumbItems = [];
while (count($currentSections) > 0) {
    $section = array_pop($currentSections);
    $breadcrumbItems[] = new BreadcrumbItem(new TextComponent($section->getName()), $section->getUrl());
}
$breadcrumb->addItems($breadcrumbItems);
$breadcrumb->addItems([new BreadcrumbItem(new TextComponent($item->getName()), $item->getUrl())]);
$pageData['HeaderVisible'] = false;
$pageData['Title'] = $item->getName();
require_once 'code/view/includes/header.php';
?>
<div class="list-layout">
    <div class="item" item-id="<?= $itemId ?>">
        <div class="item-header highlighted header1"><?= $item->getName() ?></div>
        <div class="item-content table-layout">
            <?php
            $images = $item->getImages();
            if (count($images) > 1) {
            ?>
                <div class="slider-container large-icon" index="0">
                    <div class="slider">
                        <?php foreach ($image as $image) { ?>
                            <img class="slide icon-background" src="<?= $image->getSource() ?>" />
                        <?php } ?>
                    </div>
                    <button class="slider-back-button">&#60;</button>
                    <button class="slider-forth-button">&#62;</button>
                </div>
            <?php } else { ?>
                <img class="icon large-icon icon-background" src="<?= $images[0]->getSource() ?>" />
            <?php } ?>
            <div class="table-layout center-layout">
                <div class="list-layout center-layout">
                    <div>
                        <label class="header2" for="cost">Цена:</label>
                        <span class="header2"><?= $price ?></span>
                    </div>
                    <div>
                        <label class="header2" for="count">Количество:</label>
                    </div>
                    <div class="block list-layout count-block">
                        <div class="table-layout">
                            <button type="button" class="interactive remove-button">-</button>
                            <input name="count" type="number" value="<?= $cartCount ?>" min="0" max="<?= $count ?>" />
                            <button type="button" class="interactive add-button">+</button>
                        </div>
                        <input name="count" type="range" value="<?= $cartCount ?>" min="0" max="<?= $count ?>" />
                    </div>
                </div>
            </div>
            <div class="item-buttons">
                <button class="item-header interactive favorites-button<?= $isInFavorites ? ' active' : '' ?>">
                    <?= $isInFavorites ? 'Удалить из избранного' : 'В избранное' ?></button>
            </div>
        </div>
    </div>
    <?= $breadcrumb->render() ?>
    <div class="accordion item">
        <div class="accordion-header highlighted header2">
            Описание
        </div>
        <div class="accordion-content block">
            <?= $item->getDescription() ?>
        </div>
    </div>
    <div class="accordion item">
        <div class="accordion-header highlighted header2">
            Характеристики
        </div>
        <div class="accordion-content block">
            <div class="table">
                <?php foreach ($item->getCharacteristics() as $characteristic) { ?>
                    <div class="row">
                        <div class="cell">
                            <?= $characteristic->getName() ?>
                            <div class="tooltip-container right-float">
                                <img class="tiny-icon" src="/resources/images/question.png" />
                                <div class="tooltip-content">
                                    <?= $characteristic->getDescription() ?>
                                </div>
                            </div>
                        </div>
                        <div class="cell">
                            <?= $characteristic->getValue() ?>
                            <?= $characteristic->getUnit()->getSymbol() ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php require_once 'code/view/includes/footer.php'; ?>