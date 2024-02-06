<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Pages.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/orders/Order.php';

use Model\Orders\Order;
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
$breadcrumb = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
$breadcrumb->addItems([new BreadcrumbItem(
    new TextComponent("Заказ №$orderId"),
    $order->getUrl()
)]);

$pageData['Title'] = "Заказ №$orderId";
$pageData['Breadcrumb'] = $breadcrumb;
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/header.php';
?>
<div></div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/footer.php'; ?>