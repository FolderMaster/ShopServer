<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/ItemComponent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/users/FavoritesManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/users/CartManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/items/prices/ItemPriceManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/storages/StoredItemManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Pages.php';

use Model\Users\FavoritesManager;
use Model\Users\CartManager;
use Model\Items\Item;
use Model\Storages\StoredItemManager;
use Model\Items\Prices\ItemPriceManager;
use function Control\Authorize;
use function Control\GetBreadcrumb;
use function Control\ShowError;

Authorize();
if (!isset($pageData['UserId'])) {
    ShowError();
}
$userId = $pageData['UserId'];
$pageData['Title'] = 'Корзина';
$pageData['Breadcrumb'] = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/header.php';
?>
<form class="process-order-form form" ajax="true" action="/ajax/processOrderItemSets.php" method="get" enctype="multipart/form-data" onsuccess="location.reload()">
    <div class="list-layout">
        <?php
        $totalPrice = 0;
        foreach (CartManager::getItems($userId) as $itemSet) {
            $itemId = $itemSet->getItemId();
            $item = new Item($itemId);
            $price = ItemPriceManager::getItemPrice($itemId, 1);
            $count = $itemSet->getCount();
            $isInFavorites = FavoritesManager::checkItem($userId, $itemId);
            $itemCount = StoredItemManager::getCountOfItem($itemId);
            $totalPrice += $price * $count;
            $characteristicText = '';
            $characteristicParts = [];
            foreach ($item->getCharacteristics() as $characteristic) {
                $characteristicParts[] = $characteristic->getName() . ' - ' .
                    $characteristic->getValue() . ' ' .
                    $characteristic->getUnit()->getSymbol();
            }
            $characteristicText = join(', ', $characteristicParts);
        ?>
            <div class="item" item-id="<?= $itemId ?>">
                <div class="item-header highlighted">
                    <a class="item-link" href="<?= $item->getUrl() ?>"><?= $item->getName() ?></a>
                </div>
                <div class="item-content block table-layout">
                    <div>
                        <img src="<?= $item->getImages()[0]->getSource() ?>" class="icon medium-icon icon-background" />
                    </div>
                    <div>
                        <label for="cost[<?= $itemId ?>]">Цена:</label>
                        <input class="hidden-as-text header3" id="cost[<?= $itemId ?>]" name="cost[<?= $itemId ?>]" readonly value="<?= $price ?>" /> <br />
                        <label class="header3" for="count">Количество:</label>
                        <div class="block list-layout count-block">
                            <div class="table-layout">
                                <button type="button" class="interactive remove-button">-</button>
                                <input id="count[<?= $itemId ?>]" name="count[<?= $itemId ?>]" type="number" value="<?= $count ?>" min="0" max="<?= $itemCount ?>" />
                                <button type="button" class="interactive add-button">+</button>
                            </div>
                            <input id="count[<?= $itemId ?>]" name="count[<?= $itemId ?>]" type="range" value="<?= $count ?>" min="0" max="<?= $itemCount ?>" />
                        </div>
                        <p class="item-text">Описание: <?= $item->getDescription() ?> </p>
                        <?= $characteristicText != '' ? '<p class="item-text">' . $characteristicText . '</p>' : '' ?>
                    </div>
                    <div class="item-buttons">
                        <button type="button" class="interactive item-header favorites-button<?= ($isInFavorites ? ' active' : '') ?>">
                            <?= ($isInFavorites ? 'Удалить из избранного' : 'В избранное') ?> </button>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <label class="header2" for="totalCost">Итоговая цена:</label> <input class="hidden-as-text header2" id="totalCost" name="totalCost" readonly value="<?= $totalPrice ?>" /> <br />
    <input class="interactive item-header" type="submit" name="process-order-button" <?= $totalPrice == 0 ? 'disabled="true"' : '' ?> value="Оформить" />
</form>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/footer.php'; ?>