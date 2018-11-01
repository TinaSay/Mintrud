<?php

use app\modules\staticVote\models\StaticVoteQuestion;
use yii\db\Migration;

class m170824_094751_questionnaire_children_q_fix extends Migration
{
    public function safeUp()
    {
        $questions = StaticVoteQuestion::find()
            ->all();
        /** @var StaticVoteQuestion $model */
        foreach ($questions as $model) {
            $model->question = $this->capitalize($model->question);

            if ($model->answers) {
                $answers = [];
                foreach ($model->answers as $key => $answer) {
                    $answers[$key] = $this->capitalize($answer);
                }
                $model->answers = $answers;
            }
            $model->save();
        }
    }

    public function safeDown()
    {
        echo "m170824_094751_questionnaire_children_q_fix cannot be reverted.\n";

        return true;
    }

    /**
     * @param $str
     *
     * @return string
     */
    private function capitalize($str)
    {
        return mb_strtoupper(mb_substr($str, 0, 1, 'UTF-8'), 'UTF-8') .
            mb_strtolower(mb_substr($str, 1, null, 'UTF-8'), 'UTF-8');
    }

}
