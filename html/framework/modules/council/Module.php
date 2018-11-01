<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 13:36
 */

declare(strict_types=1);


namespace app\modules\council;


use app\modules\system\components\backend\NameInterface;
use Yii;
use yii\filters\AccessControl;

class Module extends \yii\base\Module implements NameInterface
{
    /**
     * @var array
     */
    public $rules = [];

    public static function getName()
    {
        return Yii::t('system', 'Public council');
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        // if rules isset - attach AccessControl behavior
        return $this->rules ? [
            'access' => [
                'class' => AccessControl::className(),
                'user' => 'lk',
                'rules' => $this->rules,
            ],
        ] : [];
    }
}