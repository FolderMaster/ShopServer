<?php
require_once 'code/control/Pages.php';
require_once 'code/model/orders/Order.php';
require_once 'code/model/items/ItemPriceManager.php';
require_once 'code/view/components/ItemComponent.php';

use Model\Items\ItemPriceManager;
use Model\Orders\Order;
use Model\Items\Item;
use View\Components\ItemComponent;
use function Control\Authorize;
use function Control\GetBreadcrumb;

Authorize();
if (!isset($pageData['User'])) {
    require_once 'error.php';
    die;
}
$userId = $pageData['User'];
$orders = Order::getOrdersByUserId($userId);
$pageData['Title'] = 'Заказы';
$pageData['Breadcrumb'] = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
require_once 'code/view/includes/header.php';
?>
<div class="list-layout">
    <?php
    foreach ($orders as $order) {
        $status = $order->getLastStatus();
        $statusClass = $status;
        switch ($status) {
            case 'Оформление':
            case 'Обработка':
            case 'Оплата':
            case 'Отправка':
            case 'Ожидание':
            case 'Доставка':
                $statusClass = 'process';
                break;
            case 'Отмена':
            case 'Возврат':
            case 'Отказ':
                $statusClass = 'error';
                break;
            case 'Завершение':
                $statusClass = 'success';
                break;
        }
    ?>
        <div class="accordion item">
            <div class="accordion-header header2 <?= $statusClass ?>">
                Заказ №<?= $order->getId() ?> (<?= $status ?>)
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
<?php require_once 'code/view/includes/footer.php'; ?>