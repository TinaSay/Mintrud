<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 01.07.17
 * Time: 16:26
 */

namespace app\modules\cabinet\form;

use app\modules\cabinet\components\VerifyCodeInterface;
use app\modules\cabinet\models\VerifyCode;
use Yii;
use yii\base\Model;

/**
 * Class VerifyCodeForm
 *
 * @property string $sessionId
 * @property string $code
 *
 * @package app\modules\cabinet\form
 */
class VerifyCodeForm extends Model implements VerifyCodeInterface
{
    /**
     * @var null
     */
    private $sessionId = null;

    /**
     * @var null
     */
    public $code = null;

    /**
     * @var string
     */
    protected $email = '';

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['sessionId', 'code'], 'required'],
            [['sessionId'], 'string', 'max' => 128],
            [['code'], 'string', 'max' => self::CODE_LENGTH_MAX, 'min' => self::CODE_LENGTH_MIN],
            [
                'code',
                'exist',
                'targetClass' => VerifyCode::className(),
                'targetAttribute' => [
                    'sessionId' => 'session_id',
                    'code' => 'code',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sessionId' => 'Сессия',
            'code' => 'Код подтверждения',
        ];
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        $this->sessionId = Yii::$app->getSession()->getId();

        return $this->sessionId;
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
