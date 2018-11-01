<?php

use yii\helpers\Html;

?>
<h1><?= \app\modules\subscribeSend\models\SubscribeSend::getHeaderMail($module);?></h1>
<?php foreach ($arrDataSend as $data): ?>
    <div>
        <?= $data['date']; ?>
    </div>
    <div>
        <?= Html::a($data['title'], $data['url']); ?>
    </div>
<?php endforeach; ?>