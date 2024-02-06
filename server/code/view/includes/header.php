<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/Pages.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/code/control/General.php';

use function Control\GetMainMenu;
use function Control\GetMenuForGuest;
use function Control\GetMenuForUser;
use function Control\CheckVariable;
use function Control\GetMainUrl;

CheckVariable($pageData['StatusCode'], 200);
CheckVariable($pageData['Title'], '');
CheckVariable($pageData['KeyWords'], '');
CheckVariable($pageData['Description'], '');
CheckVariable($pageData['HeaderTitle'], $pageData['Title']);
CheckVariable($pageData['HeaderVisible'], true);
CheckVariable($pageData['Breadcrumb'], null);

http_response_code($pageData['StatusCode']);
?>

<!DOCTYPE html>
<html>

<head>
	<title><?= $pageData['Title'] ?></title>

	<meta name="keywords" content="<?= $pageData['KeyWords'] ?>" lang="ru">
	<meta name="author" content="Андрей Пчелинцев Александрович">
	<meta name="copyright" content="Все права защищены">
	<meta name="description" content="<?= $pageData['Description'] ?>">
	<meta name="robots" content="index, follow">

	<meta http-equiv="content-type" content="text/html;charset=UTF-8">
	<meta http-equiv="expires" content="Sun, 01 Jan 2024 00:00:00 GMT">

	<meta property="og:title" content="Dzon" />
	<meta property="og:description" content="Dzon - звонок, который вас ждёт" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="<?= GetMainUrl() ?>/resources/images/icon.png" />

	<link href="/resources/images/icon.png" rel="shortcut icon" type="image/x-icon">

	<link href="/resources/css/night-style.css" rel="stylesheet" type="text/css">
	<link href="/resources/css/main-style.css" rel="stylesheet" type="text/css">

	<script src="/resources/js/jquery-3.7.1.min.js" type="text/javascript"></script>
	<script src="/resources/js/frontend.js" type="text/javascript"></script>
</head>

<body>
	<header>
		<nav class="menu">
			<ul>
				<?= GetMainMenu()->render() ?>
				<div class="right-float">
					<?= isset($pageData['UserId']) ? GetMenuForUser()->render() : GetMenuForGuest()->render() ?>
				</div>
			</ul>
		</nav>
	</header>
	<div id="message-list"></div>
	<main>
		<article class="block">
			<?php if ($pageData['HeaderVisible']) { ?>
				<div class="header"><?= $pageData['HeaderTitle'] ?></div>
			<?php } ?>
			<?php if ($pageData['Breadcrumb']) {
				echo $pageData['Breadcrumb']->render();
			}
