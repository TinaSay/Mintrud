<?php

declare(strict_types=1);

namespace app\modules\questionnaire\models;

use app\behaviors\IpBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\modules\questionnaire\models\query\ResultAnswerTextQuery;
use Yii;

/**
 * This is the model class for table "{{%questionnaire_result}}".
 *
 * @property integer $id
 * @property integer $questionnaire_id
 * @property integer $ip
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Questionnaire $questionnaire
 * @property ResultAnswer[] $resultAnswers
 * @property ResultAnswerText[] $resultAnswerTexts
 */
class Result extends \yii\db\ActiveRecord
{
    const CAPTCHA_NAME = 'captcha';

    public $captcha;

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
        return '{{%questionnaire_result}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['questionnaire_id', 'ip'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['questionnaire_id'], 'exist', 'skipOnError' => true, 'targetClass' => Questionnaire::className(), 'targetAttribute' => ['questionnaire_id' => 'id']],
            [['captcha'], 'captcha', 'captchaAction' => 'questionnaire/question/captcha'],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
            'IpBehavior' => IpBehavior::className(),
            'TagDependencyBehavior' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'questionnaire_id' => Yii::t('system', 'Questionnaire ID'),
            'ip' => Yii::t('system', 'Ip'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaire()
    {
        return $this->hasOne(Questionnaire::className(), ['id' => 'questionnaire_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResultAnswers()
    {
        return $this->hasMany(ResultAnswer::className(), ['result_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery|ResultAnswerTextQuery
     */
    public function getResultAnswerTexts(): ResultAnswerTextQuery
    {
        return $this->hasMany(ResultAnswerText::className(), ['result_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\query\ResultQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\questionnaire\models\query\ResultQuery(get_called_class());
    }

    /**
     * @param $questionnaireId
     * @param $ip
     * @return static
     */
    public static function create($questionnaireId, $ip)
    {
        $model = new static();
        $model->questionnaire_id = $questionnaireId;
        $model->ip = $ip;
        return $model;
    }
}
