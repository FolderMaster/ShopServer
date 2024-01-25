<?php

require_once 'code/model/users/paymentMethods/BankCard.php';
require_once 'code/control/Pages.php';

use Model\Users\PaymentMethods\BankCard;
use function Control\Authorize;
use function Control\GetBreadcrumb;

Authorize();
if (!isset($pageData['User'])) {
    require_once 'error.php';
    die;
}
$userId = $pageData['User'];
$pageData['Title'] = 'Методы оплаты';
$pageData['Breadcrumb'] = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
require_once 'code/view/includes/header.php';
?>
<div class="tab-control">
    <div class="table-layout">
        <div class="tab highlighted item-header header2 active" tab-id="1">
            Банковские карты
        </div>
    </div>
    <div class="list-layout">
        <div class="accordion tab-content item active" tab-id="1">
            <?php foreach (BankCard::getBankCards($userId) as $bankCard) { ?>
                <div class="bank-card">
                    <div>Номер</div>
                    <div><?= $bankCard->getNumber() ?></div>
                    <div>Месяц/год</div>
                    <div><?= $bankCard->getExpiryMonth() ?>
                        /<?= $bankCard->getExpiryYear() ?></div>
                    <div>Собственник</div>
                    <div><?= $bankCard->getCardholderName() ?></div>
                    <div>Cvc</div>
                    <div><?= $bankCard->getCvc() ?></div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php require_once 'code/view/includes/footer.php'; ?>