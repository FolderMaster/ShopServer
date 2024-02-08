<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/model/users/paymentMethods/BankCard.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Pages.php';

use Model\Users\PaymentMethods\BankCard;
use function Control\Authorize;
use function Control\GetBreadcrumb;
use function Control\ShowError;

Authorize();
if (!isset($pageData['UserId'])) {
    ShowError();
}
$userId = $pageData['UserId'];
$pageData['Title'] = 'Методы оплаты';
$pageData['Breadcrumb'] = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/header.php';
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
            <button class="interactive item-header" type="submit">
                Добавить карту
            </button>
        </div>
    </div>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/footer.php'; ?>