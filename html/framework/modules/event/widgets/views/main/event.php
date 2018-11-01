<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 24.08.2017
 * Time: 13:32
 */

/** @var $this \yii\web\View */
/** @var $groupsModels array */
/** @var $models \app\modules\event\models\Event[] */

?>
<div class="event-container">
	<?php foreach ($groupsModels as $date => $models): ?>
	    <div class="event" data-date="<?= $date ?>">
	    	<div class="event-close"></div>
	        <?php foreach ($models as $model) : ?>
                <p class="ev-date"><?= $model->asDates() . ' ' . $model->place ?></p>
	            <a class="ev-text"
	               href="<?= $model->asUrl() ?>"><?= $model->title ?></a>
	        <?php endforeach; ?>
	    </div>
	<?php endforeach; ?>
</div>