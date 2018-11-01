<?php

namespace app\modules\redirect\models;

use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\modules\redirect\models\query\RedirectQuery;
use app\traits\BehaviorDefaultTrait;
use app\traits\HiddenAttributeTrait;
use Yii;

/**
 * This is the model class for table "{{%redirect}}".
 *
 * @property integer $id
 * @property string $from
 * @property string $redirect
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 */
class Redirect extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait, BehaviorDefaultTrait;

    const PREFIX_REDIRECT = '/';

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
        return '{{%redirect}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'redirect'], 'required'],
            [['hidden', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['from', 'redirect'], 'string', 'max' => 1000],
            [['redirect'], 'match', 'pattern' => '~^(/|https?://).*~'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Auth::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'from' => 'От куда',
            'redirect' => 'Куда',
            'hidden' => Yii::t('system', 'Hidden'),
            'created_by' => Yii::t('system', 'Created By'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return $this->behaviorsDefault();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Auth::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return RedirectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RedirectQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        return [
            'redirect' => 'Ссылка должна начинаться / или http:// или https:// '
        ];
    }


}
