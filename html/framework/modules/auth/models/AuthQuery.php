<?php

namespace app\modules\auth\models;

/**
 * This is the ActiveQuery class for [[Auth]].
 *
 * @see Auth
 */
class AuthQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Auth[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Auth|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $ip
     * @return $this
     */
    public function ip($ip)
    {
        return $this->andWhere([Auth::tableName() . '.[[ip]]' => $ip]);
    }

    /**
     * @param $login
     * @return $this
     */
    public function login($login)
    {
        return $this->andWhere([Auth::tableName() . '.[[login]]' => $login]);
    }

    /**
     * @param $blocked
     * @return $this
     */
    public function blocked($blocked)
    {
        return $this->andWhere([Auth::tableName() . '.[[blocked]]' => $blocked]);
    }

    /**
     * @param int $bindIp
     * @return $this
     */
    public function bindIp($bindIp = Auth::BIND_IP_YES)
    {
        return $this->andWhere([Auth::tableName() . '.[[bind_ip]]' => $bindIp]);
    }
}
