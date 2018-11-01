<?php

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$form = new $generator->modelClass();
$safeAttributes = $form->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $form->attributes();
}

echo "<?php\n";
?>

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
?>

<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
    }
} ?>
