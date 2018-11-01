<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 14:46
 */

declare(strict_types=1);

namespace app\modules\questionnaire\models\result;


use app\behaviors\EventBehavior;
use app\modules\questionnaire\form\Checkbox;
use app\modules\questionnaire\form\Radio;
use app\modules\questionnaire\form\Select;
use app\modules\questionnaire\form\SelectMultiple;
use app\modules\questionnaire\form\Text;
use app\modules\questionnaire\form\Textarea;
use app\modules\questionnaire\models\ResultAnswer;
use app\modules\questionnaire\models\ResultAnswerText;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class Result
 * @package app\modules\questionnaire\models\result
 */
class Result extends \app\modules\questionnaire\models\Result
{
    /**
     * @var array
     */
    public $queryParams = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        $behavior = parent::behaviors();

        return ArrayHelper::merge($behavior, [
            'ResultBehavior' => [
                'class' => EventBehavior::class,
                'events' => [
                    BaseActiveRecord::EVENT_AFTER_INSERT => [$this, 'saveResult']
                ]
            ]
        ]);
    }

    /**
     * @param AfterSaveEvent $event
     * @throws Exception
     */
    public function saveResult(AfterSaveEvent $event)
    {
        /** @var Result $model */
        $model = $event->sender;

        $radios = new Radio();
        if ($radios->load($model->queryParams) && $radios->validate()) {
            foreach ($radios->radio as $id => $radio) {
                $this->saveResultAnswer($model->id, $id, (int)$radio);
            }
        }

        $checkbox = new Checkbox();
        if ($checkbox->load($model->queryParams) && $checkbox->validate()) {
            foreach ($checkbox->checkbox as $id => $checkboxes) {
                foreach ($checkboxes as $checkboxId) {
                    $this->saveResultAnswer($model->id, $id, (int)$checkboxId);
                }
            }
        }

        $selects = new Select();
        if ($selects->load($model->queryParams) && $selects->validate()) {
            foreach ($selects->select as $id => $select) {
                $this->saveResultAnswer($model->id, $id, (int)$select);
            }
        }

        $texts = new Text();

        if ($texts->load($model->queryParams) && $texts->validate()) {
            foreach ($texts->text as $id => $text) {
                $this->saveResultAnswerText($model->id, $id, $text);
            }
        }

        $textareas = new Textarea();

        if ($textareas->load($model->queryParams) && $textareas->validate()) {
            foreach ($textareas->textarea as $id => $textarea) {
                $this->saveResultAnswerText($model->id, $id, $textarea);
            }
        }

        $selectMultiples = new SelectMultiple();
        if ($selectMultiples->load($model->queryParams) && $selectMultiples->validate()) {
            foreach ($selectMultiples->select as $id => $selects) {
                foreach ($selects as $select) {
                    $this->saveResultAnswer($model->id, $id, (int)$select);
                }
            }
        }
    }


    /**
     * @param int $resultId
     * @param int $questionId
     * @param int $answerId
     * @throws Exception
     */
    private function saveResultAnswer(int $resultId, int $questionId, int $answerId): void
    {
        if ($answerId !== 0) {
            $resultAnswer = new ResultAnswer([
                'result_id' => $resultId,
                'question_id' => $questionId,
                'answer_id' => $answerId,
            ]);
            if (!$resultAnswer->save()) {
                throw new Exception('Failed to save the object for unknown reason');
            }
        }
    }

    /**
     * @param int $resultId
     * @param int $questionId
     * @param string $text
     * @throws Exception
     */
    private function saveResultAnswerText(int $resultId, int $questionId, string $text): void
    {
        if (!empty($text)) {
            $resultAnswerText = new ResultAnswerText([
                'result_id' => $resultId,
                'question_id' => $questionId,
                'text' => $text,
            ]);
            if (!$resultAnswerText->save()) {
                throw new Exception('Failed to save the object for unknown reason');
            }
        }
    }
}