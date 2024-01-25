<?php

require_once 'code/view/components/SectionComponent.php';
require_once 'code/control/Pages.php';

use View\Components\SectionComponent;
use Model\Items\Section;

use function Control\GetBreadcrumb;
use function Control\Authorize;

Authorize();
$sections = Section::getSectionsByParentSectionId(null);
$pageData['Title'] = 'Магазин';
$pageData['Breadcrumb'] = GetBreadcrumb($_SERVER['SCRIPT_NAME']);
require_once 'code/view/includes/header.php';
?>
<div class="table-layout section-grids">
    <?php foreach ($sections as $section) {
        echo (new SectionComponent($section))->render();
    } ?>
</div>
<?php require_once 'code/view/includes/footer.php'; ?>