<?php

namespace app\modules\cabinet\models;

use Yii;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[VerifyCode]].
 *
 * @see VerifyCode
 */
class VerifyCodeQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return VerifyCode[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VerifyCode|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return VerifyCode[]|array
     */
    public function retryTimeout()
    {
        return $this->where([
            'session_id' => Yii::$app->getSession()->getId(),
        ])->andWhere([
            '<',
            'updated_at',
            new Expression('NOW() - INTERVAL ' . VerifyCode::RETRY_INTERVAL . ' MINUTE'),
        ])->all();
    }

    /**
     * @param int $limit
     *
     * @return int
     */
    public function retryLimit($limit = VerifyCode::RETRY_MAX)
    {
        return VerifyCode::deleteAll(['>=', 'retry', $limit]);
    }

    /**
     * @param null|string $sessionId
     *
     * @return int
     */
    public function cleanWithSessionId(string $sessionId = null)
    {
        if ($sessionId === null) {
            $sessionId = Yii::$app->getSession()->getId();
        }

        return VerifyCode::deleteAll(['session_id' => $sessionId]);
    }

    /**
     * @param string|null $sessionId
     *
     * @return $this
     */
    public function session(string $sessionId = null)
    {
        if ($sessionId === null) {
            $sessionId = Yii::$app->getSession()->getId();
        }

        return $this->andWhere([
            VerifyCode::tableName() . '.[[session_id]]' => $sessionId,
        ]);
    }
}
