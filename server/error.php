<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Pages.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/General.php';

use function Control\Authorize;
use function Control\CheckVariable;

if (session_status() != PHP_SESSION_ACTIVE) {
    Authorize();
}
CheckVariable($errorData['Title'], '');
CheckVariable($errorData['Description'], '');
CheckVariable($errorData['StatusCode'], 404);
$pageData['StatusCode'] = $errorData['StatusCode'];
$pageData['Title'] = 'Ошибка ' . $pageData['StatusCode'] .
    (strlen($errorData['Title']) > 0 ? ' : ' . $pageData['Title'] : '');
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/header.php';
if ($errorData['Description']) { ?>
    <p class="header3"><?= $errorData['Description'] ?></p>
<?php
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/view/includes/footer.php';
?>