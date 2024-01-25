<?php
require_once 'code/view/components/SectionComponent.php';
require_once 'code/view/components/ItemComponent.php';
require_once 'code/model/items/ItemPriceManager.php';
require_once 'code/model/items/StoredItemManager.php';
require_once 'code/model/items/characteristics/Property.php';
require_once 'code/model/users/CartManager.php';
require_once 'code/model/users/FavoritesManager.php';
require_once 'code/control/Pages.php';

use Model\Items\Characteristics\Characteristic;
use Model\Items\Characteristics\Property;
use View\Components\SectionComponent;
use View\Components\ItemComponent;
use View\Components\Breadcrumb\BreadcrumbItem;
use View\Components\Contents\TextComponent;
use Model\Items\Item;
use Model\Items\Section;
use Model\Items\StoredItemManager;
use Model\Items\ItemPriceManager;
use Model\Users\FavoritesManager;
use Model\Users\CartManager;
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
$sectionId = (int)$queryKey;

$breadcrumb = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
$childBranch = array_reverse(Section::getSectionsBranchByChildSectionId($sectionId));
$breadcrumbItems = [];
foreach ($childBranch as $branchSection) {
    $breadcrumbItems[] = new BreadcrumbItem(
        new TextComponent($branchSection->getName()),
        $branchSection->getUrl()
    );
}
$breadcrumb->addItems($breadcrumbItems);

$section = new Section($sectionId);
$sections = Section::getSectionsByParentSectionId($sectionId);
$parentBranch = Section::getSectionsBranchByParentSectionId($sectionId);
$parentBranch[] = $sectionId;
$items = [];
foreach ($parentBranch as $sectionId) {
    $items = array_merge(
        $items,
        Item::getItemsBySectionId($sectionId)
    );
}
if (isset($pageData['User'])) {
    $userId = $pageData['User'];
}
$pageData['Title'] = $section->getName();
$pageData['Breadcrumb'] = $breadcrumb;
require_once 'code/view/includes/header.php';
?>
<?php if (count($sections) > 0) { ?>
    <div>
        <p class="header2">Подразделы: </p>
        <div class="table-layout section-grids">
            <?php foreach ($sections as $section) {
                echo (new SectionComponent($section, true))->render();
            } ?>
        </div>
    </div>
<?php }
if (count($items) > 0) {
?>
    <div class="item-header block">
        <button onclick="$('.list-layout').toggleClass('table-layout');">
            <img class="small-icon" src="/resources/images/change.png" />
        </button>
    </div>
    <div class="item-content block list-layout item-grids">
        <?php foreach ($items as $item) {
            $itemId = $item->getId();
            $count = StoredItemManager::getCountOfItem($itemId);
            if ($count > 0) {
                $cartCount = 0;
                $isInFavorites = false;
                if (isset($userId)) {
                    $isInFavorites = FavoritesManager::checkItem($userId, $itemId);
                    $cartCount = CartManager::checkItemSet($userId, $itemId);
                }
        ?>
                <?= (new ItemComponent(
                    $item,
                    ItemPriceManager::getItemPrice($itemId, 1),
                    $count,
                    $cartCount,
                    $isInFavorites
                ))->render(); ?>
        <?php }
        } ?>
    </div>
<?php
}
require_once 'code/view/includes/footer.php';
?>