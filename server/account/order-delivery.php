<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Pages.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/orders/Order.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/orders/PickupPoint.php';

use Model\Orders\Order;
use Model\Orders\PickupPoint;
use const Model\Orders\RegiserOrderStatus;
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
if ($order->getLastStatus() != RegiserOrderStatus) {
    ShowError();
}

$breadcrumb = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
$breadcrumb->addItems([new BreadcrumbItem(
    new TextComponent("Заказ №$orderId"),
    $order->getUrl()
)]);

$pageData['Title'] = "Доставка заказа №$orderId";
$pageData['Breadcrumb'] = $breadcrumb;
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/header.php';
?>
<input name="order-id" type="hidden" value="<?= $order->getId() ?>" />
<input name="address" />
<select name="pickup-point-id">
    <?php foreach (PickupPoint::getPickupPoints() as $pickupPoint) { ?>
        <option value="<?= $pickupPoint->getId() ?>" class="block">
            <?php
            $images = $pickupPoint->getFiles();
            if (count($images) > 1) {
            ?>
                <div class="slider-container medium-icon left-" index="0">
                    <div class="slider">
                        <?php foreach ($image as $image) { ?>
                            <img class="slide icon-background left-float" src="<?= $image->getSource() ?>" />
                        <?php } ?>
                    </div>
                    <button class="slider-back-button">&#60;</button>
                    <button class="slider-forth-button">&#62;</button>
                </div>
            <?php } else { ?>
                <img class="icon medium-icon icon-background left-float" src="<?= $images[0]->getSource() ?>" />
            <?php } ?>
            <?= $pickupPoint->getName() ?>
            <?= $pickupPoint->getDescription() ?>
            <?= $pickupPoint->getAddress() ?>
            <div class="block">
                <?php foreach ($pickupPoint->getWorkingTimes() as $startTime => $endTime) { ?>
                    <?= $startTime ?> - <?= $endTime ?>
                <?php } ?>
            </div>
        </option>
    <?php } ?>
</select>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/footer.php'; ?>