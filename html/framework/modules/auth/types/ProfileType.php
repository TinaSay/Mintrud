<?php
/**
 * Created by PhpStorm.
 * User: cherem
 * Date: 17.11.17
 * Time: 13:44
 */

namespace app\modules\auth\types;


use app\modules\auth\models\Auth;
use yii\base\Model;

/**
 * Class ProfileType
 * @package app\modules\auth\types
 */
class ProfileType extends Model
{
    /**
     * @var
     */
    public $bind_ip;

    public $dynamicIp;


    /**
     * @var Auth
     */
    public $model;

    /**
     * ProfileType constructor.
     * @param Auth $model
     * @param array $config
     */
    public function __construct(
        Auth $model,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->bind_ip = $model->bind_ip;
        if ($this->bind_ip === Auth::BIND_IP_DYNAMIC) {
            $this->dynamicIp = $model->dynamicIp;
        }
        $this->model = $model;
    }


    /**
     * @var null|Auth
     */
    protected $auth = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['bind_ip'], 'integer'],
            [['dynamicIp'], 'ip', 'ipv4Pattern'=> '/^(?:(?:2(?:[0-4][0-9]|5[0-5])|[0-1]?[0-9]?[0-9])\.){3}(?:(?:2([0-4*][0-9*]|5[0-5*])|[0-1*]?[0-9*]?[0-9*]))$/'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return $this->model->attributeLabels();
    }

    public function attributeHints()
    {
        return $this->model->attributeHints();
    }
}