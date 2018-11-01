<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 05.07.17
 * Time: 14:46
 */

use app\core\helpers\MenuHelper;

/** @var $this \yii\web\View */

?>
<div class="navbar-collapse collapse pull-right" id="navbar-main">
    <ul class="nav navbar-nav">
        <li<?= MenuHelper::isActive('/eng/ministry/structure') ? ' class="active"' : ''; ?>>
            <a title="Structure" href="/eng/ministry/structure">Structure</a>
        </li>
        <li<?= MenuHelper::isActive('/eng/ministry/minister') ? ' class="active"' : ''; ?>>
            <a title="Minister" href="/eng/ministry/minister">Minister</a>
        </li>
        <li<?= MenuHelper::isActive('/eng/ministry/contacts') ? ' class="active"' : ''; ?>>
            <a title="Contacts" href="/eng/ministry/contacts">Contacts</a>
        </li>
        <li<?= MenuHelper::isActive('/eng/ministry/8') ? ' class="active"' : ''; ?>>
            <a title="Press Service" href="/eng/ministry/8">Press Service</a>
        </li>
    </ul>
</div>
