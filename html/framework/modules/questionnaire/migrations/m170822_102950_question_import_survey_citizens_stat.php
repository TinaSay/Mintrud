<?php

use app\modules\questionnaire\models\Question;
use app\modules\questionnaire\models\Questionnaire;
use app\modules\questionnaire\models\Result;
use app\modules\questionnaire\models\ResultAnswer;
use app\modules\questionnaire\models\ResultAnswerText;
use app\modules\questionnaire\models\Type;
use yii\db\Migration;
use yii\helpers\ArrayHelper;

class m170822_102950_question_import_survey_citizens_stat extends Migration
{
    public function safeUp()
    {
        $questionnaire = Questionnaire::findOne(['name' => 'survey_citizens']);
        $questions = Question::find()->where([
            'questionnaire_id' => $questionnaire->id,
        ])->joinWith('answers')
            ->indexBy('name')
            ->all();

        if (($fh = fopen(__DIR__ . '/data/survey_citizens_results.csv', 'r')) !== false) {
            while (($row = fgetcsv($fh, 0, ';', '"')) !== false) {
                /*
[0] => 13.10.2015 11:38
    [1] =>  Москва
    [2] => детский приют \"Забота\"
    [3] => Москва ул. Преснякова, 34
    [4] => Социальный приют для детей и подростков
    [5] => Полностью удовлетворен
    [6] => Затрудняюсь ответить
    [7] => На среднем уровне
    [8] => Чем-то удовлетворен, чем-то нет
    [9] => Скорее да
    [10] => Затрудняюсь ответить
    [11] => Не могу оценить
    [12] => Нет
    [13] => Не могу оценить
    [14] => Не могу оценить
    [15] => Да
    [16] => Да
    [17] => Да
    [18] => Скорее нет, чем да
    [19] => Скорее нет, чем да
    [20] => люди все хорошие
    [21] => но сделать ничего нельзя

                 */

                $questionCodeAlias = [
                    1 => 'region',
                    'org',
                    'city',
                    'q2',
                    'q3',
                    'q4',
                    'q5',
                    'q6',
                    'q7',
                    'q8',
                    'q9_1',
                    'q9_2',
                    'q9_3',
                    'q9_4',
                    'q9_5',
                    'q9_6',
                    'q9_7',
                    'q10',
                    'q11',
                    'q12_plus',
                    'q12_minus',
                ];

                $created_at = DateTime::createFromFormat('d.m.Y H:i', $row[0]);
                if ($created_at) {
                    $created_at = $created_at->format('Y-m-d H:i:s');
                } else {
                    $created_at = new \yii\db\Expression('NOW()');
                }
                $this->insert(Result::tableName(), [
                    'questionnaire_id' => $questionnaire->id,
                    'ip' => 0,
                    'created_at' => $created_at,
                    'updated_at' => $created_at,
                ]);
                $result_id = $this->getDb()->getLastInsertID();
                foreach ($row as $key => $field) {
                    $field = trim($field);
                    if (isset($questionCodeAlias[$key]) &&
                        $question = ArrayHelper::getValue($questions, $questionCodeAlias[$key])
                    ) {
                        if ($question->type == Type::TYPE_ID_TEXT ||
                            $question->type == Type::TYPE_ID_TEXTAREA
                        ) {
                            $this->insert(ResultAnswerText::tableName(), [
                                'result_id' => $result_id,
                                'question_id' => $question->id,
                                'text' => $field,
                                'created_at' => $created_at,
                                'updated_at' => $created_at,
                            ]);
                        } else {
                            foreach ($question->answers as $answer) {
                                if ($field == $answer->title) {
                                    $this->insert(ResultAnswer::tableName(), [
                                        'result_id' => $result_id,
                                        'question_id' => $question->id,
                                        'answer_id' => $answer->id,
                                        'created_at' => $created_at,
                                        'updated_at' => $created_at,
                                    ]);
                                    break;
                                }
                            }
                        }

                    }
                }
            }
            fclose($fh);
        }

    }

    public function safeDown()
    {
        $questionnaire = Questionnaire::findOne(['name' => 'survey_citizens']);
        if ($questionnaire) {
            Result::deleteAll(['questionnaire_id' => $questionnaire->id]);
            $questions = Question::find()->where([
                'questionnaire_id' => $questionnaire->id,
            ])->asArray()->all();
            foreach ($questions as $question) {
                ResultAnswer::deleteAll(['question_id' => $question['id']]);
                ResultAnswerText::deleteAll(['question_id' => $question['id']]);
            }
        }
        echo "m170822_102950_question_import_survey_citizens_stat - reverted.\n";

    }

}
