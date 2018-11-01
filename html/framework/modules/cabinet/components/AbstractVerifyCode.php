<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.07.17
 * Time: 14:10
 */

namespace app\modules\cabinet\components;

use app\modules\cabinet\models\VerifyCode;
use yii\base\Model;

/**
 * Class AbstractVerifyCode
 *
 * @package app\modules\cabinet\components
 */
abstract class AbstractVerifyCode extends Model implements VerifyCodeInterface
{
    const SCENARIO_VERIFY_CODE = 'verifyCode';

    /**
     * @var null
     */
    protected $email = '';

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email'], 'string', 'max' => 64],
            [['email'], 'required'],
            [['email'], 'email'],
            [
                'email',
                'exist',
                'targetClass' => VerifyCode::className(),
                'targetAttribute' => [
                    'email' => 'attribute',
                ],
                'on' => [self::SCENARIO_VERIFY_CODE],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
        ];
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function existsEmailInVerify(): bool
    {
        if (empty($this->email)) {
            return false;
        }

        return VerifyCode::find()->where([
            'like',
            'attribute',
            $this->getEmail(),
            false,
        ])->exists();
    }
}
