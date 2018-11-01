<?php

namespace app\modules\testing\models;

use app\behaviors\UploadImageBehavior;
use krok\extend\behaviors\TagDependencyBehavior;
use krok\extend\interfaces\HiddenAttributeInterface;
use krok\extend\traits\HiddenAttributeTrait;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%testing_question}}".
 *
 * @method null|string getDownloadUrl($attribute)
 * @method null|string getThumbUrl($attribute, $thumb)
 *
 * @property integer $id
 * @property integer $testId
 * @property integer $categoryId
 * @property string $title
 * @property integer $multiple
 * @property string $src
 * @property integer $hidden
 * @property integer $position
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Testing $test
 * @property TestingQuestionCategory $category
 * @property TestingQuestionAnswer[] $answers
 */
class TestingQuestion extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    const MULTIPLE_NO = 0;
    const MULTIPLE_YES = 1;

    const UPLOAD_DIRECTORY = 'testing';

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%testing_question}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'UploadImageBehavior' => [
                'class' => UploadImageBehavior::class,
                'attribute' => 'src',
                'scenarios' => [
                    self::SCENARIO_DEFAULT,
                ],
                'uploadDirectory' => '@public/' . self::UPLOAD_DIRECTORY,
            ],
            'TagDependency' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @param $src
     *
     * @return bool|string
     */
    public static function getImage($src)
    {
        return Yii::getAlias('@web/uploads/' . self::UPLOAD_DIRECTORY . '/' . $src);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testId', 'categoryId', 'multiple', 'hidden', 'position'], 'integer'],
            [['title'], 'required'],
            ['multiple', 'default', 'value' => self::MULTIPLE_NO],
            [['createdAt', 'updatedAt'], 'safe'],
            [['title'], 'string', 'max' => 4096],
            [
                ['testId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Testing::class,
                'targetAttribute' => ['testId' => 'id'],
            ],
            [
                ['categoryId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => TestingQuestionCategory::class,
                'targetAttribute' => ['categoryId' => 'id'],
            ],
            [
                ['src'],
                'image',
                'skipOnEmpty' => true,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'testId' => 'Тест',
            'categoryId' => 'Категория вопроса',
            'title' => 'Название',
            'multiple' => 'Множественный выбор',
            'hidden' => 'Скрыт',
            'src' => 'Изображение',
            'position' => 'Сортировка',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }


    /**
     * @return array
     */
    public static function getMultipleList()
    {
        return [
            self::MULTIPLE_NO => 'Нет',
            self::MULTIPLE_YES => 'Да',
        ];
    }

    /**
     * @return string
     */
    public function getMultiple()
    {
        return ArrayHelper::getValue(static::getMultipleList(), $this->multiple);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTest()
    {
        return $this->hasOne(Testing::class, ['id' => 'testId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(TestingQuestionCategory::class, ['id' => 'categoryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(TestingQuestionAnswer::class, ['testQuestionId' => 'id'])
            ->andOnCondition(TestingQuestionAnswer::tableName() . '.[[hidden]] = :hidden ',
                [
                    ':hidden' => TestingQuestionAnswer::HIDDEN_NO,
                ]);
    }

    /**
     * @param array $answers
     */
    public function setQuestionAnswers($answers)
    {
        $old_ids = ArrayHelper::getColumn($this->getQuestionAnswers(), 'id');
        $old_ids = array_diff($old_ids, [null]);
        $transaction = Yii::$app->db->beginTransaction();
        if (!empty($answers)) {
            $sort = 0;
            foreach ($answers as $answer) {
                $sort++;
                if ($answer['id']) {
                    ArrayHelper::remove($old_ids, $answer['id']);
                }
                $model = null;
                if ($answer['id']) {
                    $model = ArrayHelper::getValue($this->getQuestionAnswers(), $answer['id'], null);
                }
                if (!$model) {
                    $model = new TestingQuestionAnswer([
                        'testId' => $this->testId,
                        'testQuestionId' => $this->id,
                    ]);
                }
                $model->load($answer, '');
                $model->save();
            }
        }
        if (!empty($old_ids)) {
            //QuestionnaireResultAnswer::deleteAll(['questionnaire_answer_id' => $old_ids]);
            TestingQuestionAnswer::deleteAll(['id' => $old_ids]);
        }
        $transaction->commit();
    }

    /**
     * @return TestingQuestionAnswer[]
     */
    public function getQuestionAnswers()
    {
        if ($this->answers) {
            return $this->answers;
        }

        return [
            new TestingQuestionAnswer([
                'testId' => $this->testId,
                'testQuestionId' => $this->id,
                'hidden' => TestingQuestionAnswer::HIDDEN_NO,
                'position' => 1,
                'right' => TestingQuestionAnswer::RIGHT_YES,
            ]),
        ];
    }
}
