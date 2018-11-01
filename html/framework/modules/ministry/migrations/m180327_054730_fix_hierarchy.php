<?php

use app\modules\ministry\models\Ministry;
use yii\db\Migration;

/**
 * Class m180327_054730_fix_hierarchy
 */
class m180327_054730_fix_hierarchy extends Migration
{

    protected $position = 0;

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        Ministry::updateAll(
            [
                'url' => new \yii\db\Expression('SUBSTR([[url]], 2)'),
            ],
            ['LIKE', 'url', '\/%', false]
        );

        $parents = Ministry::find()->where([
            'REGEXP',
            'url',
            '^([a-z]+)$',
        ])->orderBy(['id' => SORT_ASC])->all();

        $processedIds = [];

        foreach ($parents as $model) {
            $this->position++;
            array_push($processedIds, $model->id);
            Ministry::updateAll(['position' => $this->position], ['id' => $model->id]);
            $this->updateChildren($model);
            $this->recursiveGetChildren($model);
        }

        $parents = Ministry::find()->where([
            'IS',
            'parent_id',
            null,
        ])->andWhere([
            'NOT IN',
            'id',
            $processedIds,
        ])->orderBy(['id' => SORT_ASC])->all();

        foreach ($parents as $model) {
            $this->position++;
            Ministry::updateAll(['position' => $this->position], ['id' => $model->id]);
            $this->updateChildren($model);
            $this->recursiveGetChildren($model);
        }

    }


    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180327_054730_fix_hierarchy cannot be reverted.\n";

        return true;
    }


    protected function recursiveGetChildren(Ministry $model)
    {
        $list = Ministry::find()
            ->where([
                'LIKE',
                'url',
                $model->url . '\/%',
                false,
            ])->all();

        if ($list) {
            foreach ($list as $row) {
                $this->position++;
                $this->updateChildren($row);
                $this->recursiveGetChildren($row);
            }
        }
    }

    protected function updateChildren(Ministry $model)
    {
        Ministry::updateAll([
            'depth' => $model->depth + 1,
            'parent_id' => $model->id,
            'position' => new \yii\db\Expression('CONCAT(' . $this->position . ' * 10, 0, `id` % 100)'),
        ], [
            'LIKE',
            'url',
            $model->url . '\/%',
            false,
        ]);
    }
}
