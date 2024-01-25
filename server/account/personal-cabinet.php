<?php

require_once 'code/model/users/UserConfidentional.php';
require_once 'code/model/users/UserProfile.php';
require_once 'code/control/Pages.php';

use Model\Users\UserConfidential;
use Model\Users\UserProfile;
use function Control\Authorize;
use function Control\GetBreadcrumb;

Authorize();
if (!isset($pageData['User'])) {
    require_once 'error.php';
    die;
}
$userId = $pageData['User'];
$userProfile = new UserProfile($userId);
$userConfidential = new UserConfidential($userId);
$pageData['Title'] = 'Личный кабинет';
$pageData['Breadcrumb'] = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
require_once 'code/view/includes/header.php';
?>
<div class="list-layout tab-control">
    <div class="item table-layout center-layout">
        <div>
            <img class="icon big-icon cicle" src="<?= $userProfile->getAvatar()->getSource() ?>" />
        </div>
        <div>
            <p class="item-text text-align-center">ФИО: <?= $userProfile->getFullName() ?> </p>
            <p class="item-text text-align-center">Электронная почта: <?= $userConfidential->getEmailAddress() ?> </p>
            <p class="item-text text-align-center">Дата рождения: <?= $userConfidential->getBirthDate()->format('d.m.Y') ?> </p>
            <p class="item-text text-align-center">Номер телефона: <?= $userConfidential->getPhoneNumber() ?> </p>
        </div>
    </div>
    <div class="accordion item">
        <div class="tab-accordion-header highlighted header2">
            Смена профиля
        </div>
        <div class="accordion-content block">
            <form>
                <fieldset>
                    <label for="name">ФИО:</label>
                    <input type="text" id="name" name="name" /><br />
                    <label for="avatar">Аватарка:</label>
                    <input type="file" id="avatar" name="avatar" /><br />
                    <label for="birthDate">Дата рождения:</label>
                    <input type="date" id="birthDate" name="birthDate" /><br />
                    <button class="item-header" type="submit" disabled>Отправить</button>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="accordion item">
        <div class="tab-accordion-header highlighted header2">
            Смена электронной почты
        </div>
        <div class="accordion-content block">
            <form>
                <fieldset>
                    <label for="email">Новая электронная почта:</label>
                    <input type="email" id="email" name="email" /><br />
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" /><br />
                    <button class="item-header" type="submit" disabled>Отправить</button>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="accordion item">
        <div class="tab-accordion-header highlighted header2">
            Смена номера телефона
        </div>
        <div class="accordion-content block">
            <form>
                <fieldset>
                    <label for="phoneNumber">Новый номер телефона:</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" /><br />
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" /><br />
                    <button class="item-header" type="submit" disabled>Отправить</button>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="accordion item">
        <div class="tab-accordion-header highlighted header2">
            Смена пароля
        </div>
        <div class="accordion-content block">
            <form>
                <fieldset>
                    <label for="oldPassword">Старый пароль:</label>
                    <input type="password" id="oldPassword" name="oldPassword" /><br />
                    <label for="newPassword">Новый пароль:</label>
                    <input type="password" id="newPassword" name="newPassword" /><br />
                    <label for="newRepeatPassword">Повторение нового пароля:</label>
                    <input type="password" id="newRepeatPassword" name="newRepeatPassword" /><br />
                    <button class="item-header" type="submit" disabled>Отправить</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<?php require_once 'code/view/includes/footer.php'; ?>