<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/ItemComponent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/users/FavoritesManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/users/CartManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/items/prices/ItemPriceManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/storages/StoredItemManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Pages.php';

use View\Components\ItemComponent;
use Model\Items\Item;
use Model\Users\FavoritesManager;
use Model\Users\CartManager;
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
$pageData['Title'] = 'Избранное';
$pageData['Breadcrumb'] = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/header.php';
?>
<div class="list-layout">
    <?php foreach (FavoritesManager::getItems($userId) as $itemId) { ?>
        <?= (new ItemComponent(
            new Item($itemId),
            ItemPriceManager::getItemPrice($itemId, 1),
            StoredItemManager::getCountOfItem($itemId),
            CartManager::getItemCount($userId, $itemId),
            true
        ))->render(); ?>
    <?php } ?>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/footer.php'; ?>