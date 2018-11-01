<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 27.06.17
 * Time: 11:14
 */

use app\modules\council\assets\CouncilVoteAsset;
use yii\widgets\ActiveForm;

CouncilVoteAsset::register($this);

/* @var $this yii\web\View */
/* @var $discussionModel \app\modules\council\models\CouncilDiscussion */
/* @var $model \app\modules\council\models\CouncilDiscussionVote */
?>
    <div class="border-block block-arrow block-arrow--top-title fix-block-aside" id="addVote">
        <h4 class="text-uppercase text-prime pd-bottom-15">Ваше мнение</h4>
        <?php $form = ActiveForm::begin([
            'action' => ['vote', 'id' => $discussionModel->id],
            'options' => ['class' => 'form vote-form'],
            'enableClientScript' => false,
        ]); ?>
        <?= $form->field($model, 'vote')->radio([
            'class' => 'hidden',
            'value' => $model::VOTE_PLACET,
            'id' => 'vote_up',
        ], false)->label(false); ?>
        <?= $form->field($model, 'vote')->radio([
            'class' => 'hidden',
            'value' => $model::VOTE_ABSTAIN,
            'id' => 'vote_neutral',
        ], false)->label(false); ?>
        <label class="wrap-vote-btn" for="vote_up">
            <button class="vote-btn vote-btn--blue btn btn-block btn-primary submit">Поддерживаю</button>
        </label>
        <label class="wrap-vote-btn" for="vote_neutral">
            <button class="vote-btn vote-btn--gray btn btn-block btn-primary submit">Воздерживаюсь</button>
        </label>
        <span class="wrap-vote-btn vote-btn vote-btn--red mr-bottom-0 btn btn-block btn-primary" data-toggle="modal"
              data-target="#modalComment">Против
        </span>
        <?php ActiveForm::end(); ?>
    </div>

<?php $this->beginBlock('modelComment'); ?>
    <div id="modalComment" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        Для учета голоса «Против», пожалуйста, обоснуйте Ваше решение в виде
                        комментария. </h4>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin([
                        'action' => ['vote', 'id' => $discussionModel->id],
                        'options' => [
                            'class' => 'form-comment form-popup',
                        ],
                        'enableClientScript' => false,
                        'fieldConfig' => [
                            'template' => '{label}' . PHP_EOL . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                        ],
                    ]); ?>
                    <?= $form->field($model, 'vote')->hiddenInput(['value' => $model::VOTE_CONTRA])->label(false); ?>
                    <div class="error-message"></div>
                    <?= $form->field($model, 'comment', [
                        'options' => [
                            'class' => 'form-group form-group--placeholder-fix',
                        ],
                        'labelOptions' => [
                            'class' => 'placeholder',
                        ],
                    ])->textarea([
                        'class' => "form-control",
                        'id' => "comment",
                        'required' => true,

                    ])->label('Добавьте свой комментарий....'); ?>
                    <div>
                        <button type="submit" class="btn btn-primary btn-md">Опубликовать</button>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>

        </div>
    </div>
<?php $this->endBlock(); ?>