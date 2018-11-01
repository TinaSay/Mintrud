<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 10.07.17
 * Time: 12:23
 */

/* @var $this yii\web\View */
/* @var $baseAuthUrl string */

use yii\authclient\widgets\AuthChoice;

?>
<?php $authAuthChoice = AuthChoice::begin([
    'popupMode' => false,
    'autoRender' => true,
    'baseAuthUrl' => $baseAuthUrl,
]); ?>
<ul class="auth-list-network">
    <?php foreach ($authAuthChoice->getClients() as $client) : ?>
        <li><?php $authAuthChoice->clientLink($client, '') ?></li>
    <?php endforeach; ?>
</ul>
<?php AuthChoice::end(); ?>
