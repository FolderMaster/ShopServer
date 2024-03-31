<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/SectionComponent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/ItemComponent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/items/prices/ItemPriceManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/storages/StoredItemManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/items/characteristics/Property.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/users/CartManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/users/FavoritesManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Pages.php';

use Model\Items\Characteristics\Characteristic;
use Model\Items\Characteristics\Property;
use View\Components\SectionComponent;
use View\Components\ItemComponent;
use View\Components\Breadcrumb\BreadcrumbItem;
use View\Components\Contents\TextComponent;
use Model\Items\Item;
use Model\Items\Section;
use Model\Storages\StoredItemManager;
use Model\Items\Prices\ItemPriceManager;
use Model\Users\FavoritesManager;
use Model\Users\CartManager;
use function Control\Authorize;
use function Control\GetBreadcrumb;
use function Control\ShowError;

Authorize();
if (!isset($_GET['id'])) {
    ShowError();
}
$id = $_GET['id'];
if (!is_numeric($id)) {
    ShowError();
}
$sectionId = (int)$id;
if (!Section::checkId($sectionId)) {
    ShowError();
}
$name = $_GET['name'] ?? '';
$characteristicValues = $_GET['characteristicValues'] ?? null;
$breadcrumb = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
$childBranch = array_reverse(
    Section::getSectionsBranchByChildSectionId($sectionId)
);
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
        Item::getItemsBySectionId($sectionId, $name, $characteristicValues)
    );
}
if (isset($pageData['UserId'])) {
    $userId = $pageData['UserId'];
}
$pageData['Title'] = $section->getName();
$pageData['Breadcrumb'] = $breadcrumb;
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/header.php';
?>
<?php if (count($sections) > 0) { ?>
    <div>
        <p class="header2">Подразделы:</p>
        <div class="table-layout section-grids">
            <?php foreach ($sections as $section) {
                echo (new SectionComponent($section, true))->render();
            } ?>
        </div>
    </div>
<?php } ?>
<form method="get" action="/shop/section.php">
    <input name="id" value="<?= $sectionId ?>" type="hidden" />
    <div class="item-header highlighted block">
        <button onclick="$('.search-panel').toggle();" type="button">
            <img class="small-icon" src="/resources/images/show.png" />
        </button>
        <button onclick="$('.list-layout').toggleClass('table-layout');" type="button">
            <img class="small-icon" src="/resources/images/change.png" />
        </button>
        <div class="right-float">
            <input type="search" name="name" value="<?= $name ?>" />
            <button button="sumbit">
                <img class="small-icon" src="/resources/images/search.png" />
            </button>
        </div>
    </div>
    <div class="block search-panel">
        <?php foreach ($childBranch as $branchSection) {
            $characteristics = Characteristic::getCharacteristicsBySectionId($branchSection->getId());
            if (count($characteristics) > 0) { ?>
                <fieldset class="table-layout">
                    <legend><?= $branchSection->getName() ?></legend>
                    <?php foreach ($characteristics as $characteristic) {
                        $property = new Property($characteristic->getPropertyId());
                        $type = '';
                        switch ($property->getType()) {
                            case 'Real':
                            case 'Interger':
                                $type = 'number';
                                break;
                            case 'Date':
                                $type = 'date';
                                break;
                            case 'String':
                                $type = 'text';
                                break;
                        }
                        $units = $property->getUnits();
                        $characteristicId = $characteristic->getId();
                    ?>
                        <label><?= $characteristic->getName() ?></label>
                        <input name="characteristicValues[<?= $characteristicId ?>][value]" type="<?= $type ?>" value="<?= $characteristicValues[$characteristicId]['value'] ?? null ?>" />
                        <?php if (count($units) > 0) {
                            $selectedUnitId = $characteristicValues[$characteristicId]['unit'] ?? null;
                        ?>
                            <select name="characteristicValues[<?= $characteristicId ?>][unit]">
                                <option disabled <?= $selectedUnitId ? '' : 'selected' ?>>null</option>
                                <?php foreach ($units as $unit) {
                                    $unitId = $unit->getId();
                                ?>
                                    <option value="<?= $unitId ?>" <?= $selectedUnitId == $unitId ? 'selected' : '' ?>><?= $unit->getName() ?></option>
                                <?php } ?>
                            </select>
                    <?php }
                    } ?>
                </fieldset>
        <?php }
        } ?>
    </div>
</form>
<?php if (count($items) > 0) { ?>
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
                } ?>
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
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/footer.php';
?>