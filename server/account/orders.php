<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Pages.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/orders/Order.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/items/prices/ItemPriceManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/components/ItemComponent.php';

use Model\Items\Prices\ItemPriceManager;
use Model\Orders\Order;
use const Model\Orders\RegiserOrderStatus;
use const Model\Orders\ProcessingOrderStatus;
use const Model\Orders\PaymentOrderStatus;
use const Model\Orders\DeliveryOrderStatus;
use const Model\Orders\SendingOrderStatus;
use const Model\Orders\WaitingOrderStatus;
use const Model\Orders\CancelOrderStatus;
use const Model\Orders\RefusalOrderStatus;
use const Model\Orders\ReturnOrderStatus;
use const Model\Orders\CompletionOrderStatus;
use Model\Items\Item;
use View\Components\ItemComponent;
use function Control\Authorize;
use function Control\GetBreadcrumb;
use function Control\ShowError;

Authorize();
if (!isset($pageData['UserId'])) {
    ShowError();
}
$userId = $pageData['UserId'];
$orders = Order::getOrdersByUserId($userId);
$pageData['Title'] = 'Заказы';
$pageData['Breadcrumb'] = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/header.php';
?>
<div class="list-layout">
    <?php
    foreach ($orders as $order) {
        $status = $order->getLastStatus();
        $orderProcessStatus = null;
        $statusClass = $status;
        switch ($status) {
            case RegiserOrderStatus:
            case ProcessingOrderStatus:
            case PaymentOrderStatus:
            case DeliveryOrderStatus:
            case SendingOrderStatus:
            case WaitingOrderStatus:
                $statusClass = 'process';
                break;
            case CancelOrderStatus:
            case RefusalOrderStatus:
            case ReturnOrderStatus:
                $statusClass = 'error';
                break;
            case CompletionOrderStatus:
                $statusClass = 'success';
                break;
        }
    ?>
        <div class="accordion item">
            <div class="accordion-header header2 <?= $statusClass ?>">
                <a class="item-link" href="<?= $order->getUrl() ?>">
                    Заказ №<?= $order->getId() ?> (<?= $status ?>)
                </a>
                <div class="right-float header2">
                    <?= $order->getLastDate()->format('d.m.Y') ?>
                </div>
            </div>
            <div class="accordion-content list-layout block">
                <?php foreach ($order->getItemSets() as $itemSet) {
                    $item = new Item($itemSet->getItemId());
                    echo (new ItemComponent(
                        $item,
                        ItemPriceManager::getItemPrice(
                            $item->getId(),
                            1,
                            $order->getPaymentDateTime() ?? new DateTime()
                        ),
                        $itemSet->getCount(),
                        0,
                        false,
                        false
                    ))->render();
                } ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/footer.php'; ?>