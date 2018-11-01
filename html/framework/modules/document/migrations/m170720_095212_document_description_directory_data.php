<?php

use app\modules\directory\rules\type\TypeInterface;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Query;

class m170720_095212_document_description_directory_data extends Migration
{
    public function safeUp()
    {
        $directories = (new Query())->from('{{%directory}}')
            ->andWhere(
                [
                    'type' => TypeInterface::TYPE_DESCRIPTION_DIRECTORY,
                ]
            )
            ->all();

        foreach ($directories as $directory) {
            $this->insert('{{%document_description_directory}}',
                [
                    'directory_id' => $directory['id'],
                    'text' => $directory['title'],
                    'created_at' => new Expression('NOW()'),
                    'updated_at' => new Expression('NOW()'),
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
        echo "m170720_095212_document_description_directory_data cannot be reverted.\n";

        return false;
    }
    */
}
