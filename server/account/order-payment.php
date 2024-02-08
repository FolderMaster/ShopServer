<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Pages.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/orders/Order.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/items/prices/CurrencyUnit.php';

use Model\Orders\Order;
use Model\Items\Prices\CurrencyUnit;
use const Model\Orders\ProcessingOrderStatus;
use function Control\Authorize;
use function Control\GetBreadcrumb;
use function Control\ShowError;
use View\Components\Breadcrumb\BreadcrumbItem;
use View\Components\Contents\TextComponent;

Authorize();
if (!isset($pageData['UserId'])) {
    ShowError();
}
if (!isset($_GET['id'])) {
    ShowError();
}
$id = $_GET['id'];
if (!is_numeric($id)) {
    ShowError();
}
$orderId = (int)$id;
$userId = $pageData['UserId'];
if (!Order::checkId($orderId, $userId)) {
    ShowError();
}
$order = new Order($orderId);
if ($order->getLastStatus() != ProcessingOrderStatus) {
    ShowError();
}

$breadcrumb = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
$breadcrumb->addItems([new BreadcrumbItem(
    new TextComponent("Заказ №$orderId"),
    $order->getUrl()
)]);

$pageData['Title'] = "Оплата заказа №$orderId";
$pageData['Breadcrumb'] = $breadcrumb;
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/header.php';
?>
<select name="currency-unit-id">
    <?php foreach (CurrencyUnit::getCurrencyUnits() as $currencyUnit) { ?>
        <option value="<?= $currencyUnit->getId() ?>">
            <?= $currencyUnit->getSymbol() ?>
        </option>
    <?php } ?>
</select>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/footer.php'; ?>