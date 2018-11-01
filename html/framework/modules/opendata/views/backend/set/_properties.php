<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 07.08.17
 * Time: 11:30
 */

use app\modules\opendata\models\OpendataSetProperty;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\opendata\models\OpendataSet */
/* @var $properties app\modules\opendata\models\OpendataSetProperty[] */

?>
    <div class="row">
        <h4 class="card-title pull-left">Свойства набора данных</h4>
        <a href="#" class="btn btn-success pull-right create-property">Создать свойство</a>
    </div>
<?php if ($properties): ?>
    <div class="property-container">
        <?php foreach ($properties as $row): ?>
            <div class="row property">
                <div class="col-md-4">
                    <?= $form->field($row, '[' . $row->id . ']name')->textInput(['maxlength' => true]); ?>

                    <?= $form->field($row, '[' . $row->id . ']delete')
                        ->hiddenInput(['class' => 'hidden delete'])->label(false); ?>
                    <?= $form->field($row,
                        '[' . $row->id . ']id')
                        ->hiddenInput(['class' => 'property-id'])->label(false); ?>
                    <?= $form->field($row, '[' . $row->id . ']passport_id')
                        ->hiddenInput(['class' => 'property-id'])->label(false); ?>
                    <?= $form->field($row, '[' . $row->id . ']set_id')
                        ->hiddenInput(['class' => 'property-id'])->label(false); ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($row, '[' . $row->id . ']title')->textInput(['maxlength' => true]); ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($row,
                        '[' . $row->id . ']type')->dropDownList(OpendataSetProperty::getTypeList()); ?>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <a href="#"
                           class="btn btn-danger delete-property<?php if (count($properties) <= 1): ?> hidden<?php endif; ?>">&times;</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>