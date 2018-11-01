<?php

namespace app\modules\testing\models\query;

use app\modules\testing\models\Testing;
use app\modules\testing\models\TestingResult;
use Yii;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[Testing]].
 *
 * @see Testing
 */
class TestingQuery extends \yii\db\ActiveQuery
{

    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere([Testing::tableName() . '.[[hidden]]' => Testing::HIDDEN_NO]);
    }

    /**
     * @inheritdoc
     * @return Testing[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Testing|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return $this
     */
    public function notAnswered()
    {
        $ip = ip2long(Yii::$app->request->getUserIP());
        $condition = '(' . new Expression(TestingResult::tableName() . '.[[testId]]') . ' = ' . new Expression(Testing::tableName() . '.[[id]]') .
            ' AND (' . new Expression(TestingResult::tableName() . '.[[ip]]') . ' = :ip';
        $params = [':ip' => $ip];

        if (!Yii::$app->get('owner')->getIsGuest()) {
            $condition .= ' OR ' . new Expression(TestingResult::tableName() . '.[[createdBy]]') . ' = :createdBy';
            $params[':createdBy'] = Yii::$app->user->getId();
        }

        $condition .= "))";

        return $this->leftJoin(
            TestingResult::tableName(),
            $condition,
            $params
        )->andWhere(
            ['IS', TestingResult::tableName() . " . [[id]]", new Expression('NULL')]
        );
    }
}
