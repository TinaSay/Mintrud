<?php

use app\modules\directory\rules\type\TypeInterface;
use yii\db\Migration;
use yii\db\Query;

class m170807_081630_document_update_data extends Migration
{
    public function safeUp()
    {
        $directions = (new Query())
            ->select(['d.id', 'd.title', 'dd.directory_id'])
            ->from('{{%document_direction}} d')
            ->innerJoin(
                '{{%document_description_directory}} dd',
                'dd.[[id]] = d.[[document_description_directory_id]]'
            )->all();

        foreach ($directions as $direction) {

            $directory = (new Query())
                ->from('{{%directory}}')
                ->andWhere(['parent_id' => $direction['directory_id']])
                ->andWhere(['title' => $direction['title']])
                ->andWhere(['type' => TypeInterface::TYPE_DIRECTION])
                ->limit(1)
                ->one();

            $this->update(
                '{{%document_direction}}',
                [
                    'directory_id' => $directory['id'],
                    'updated_at' => (new DateTime())->format('Y-m-d'),
                ],
                [
                    'id' => $direction['id']
                ]
            );
        }
    }

    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170807_081630_document_update_data cannot be reverted.\n";

        return false;
    }
    */
}
