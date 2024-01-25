<?php

require_once 'code/view/components/ItemComponent.php';
require_once 'code/model/users/FavoritesManager.php';
require_once 'code/model/users/CartManager.php';
require_once 'code/model/items/ItemPriceManager.php';
require_once 'code/model/items/StoredItemManager.php';
require_once 'code/control/Pages.php';

use View\Components\ItemComponent;
use Model\Items\Item;
use Model\Users\FavoritesManager;
use Model\Users\CartManager;
use Model\Items\StoredItemManager;
use Model\Items\ItemPriceManager;
use function Control\Authorize;
use function Control\GetBreadcrumb;

Authorize();
if (!isset($pageData['User'])) {
    require_once 'error.php';
    die;
}
$userId = $pageData['User'];
$pageData['Title'] = 'Избранное';
$pageData['Breadcrumb'] = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
require_once 'code/view/includes/header.php';
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
<?php require_once 'code/view/includes/footer.php'; ?>