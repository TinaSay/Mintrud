<?php

use yii\db\Migration;

class m171018_053136_appeal_files extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%appeal}}', 'email',
            $this->string()->notNull()->defaultValue('')->after('comment')
        );

        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%appeal_files}}', [
            'id' => $this->primaryKey(),
            'appeal_id' => $this->integer()->null()->defaultValue(null),
            'name' => $this->string(255)->notNull()->defaultValue(''),
            'src' => $this->string(64)->notNull()->defaultValue(''),
            'created_at' => $this->dateTime()->null()->defaultValue(null),
            'updated_at' => $this->dateTime()->null()->defaultValue(null),
        ], $options);


        $this->addForeignKey(
            'fk-appeal_files-appeal',
            '{{%appeal_files}}',
            'appeal_id',
            '{{%appeal}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        (new \app\modules\config\models\Config([
            'name' => 'appeal_debug',
            'label' => 'Сохранять логи и файлы обращений (1/0)',
            'value' => '1',
        ]))->save();
    }

    public function safeDown()
    {
        $this->dropColumn('{{%appeal}}', 'email');
        $this->dropForeignKey('fk-appeal_files-appeal', '{{%appeal_files}}');
        $this->delete('{{%appeal_files}}');
        $this->dropTable('{{%appeal_files}}');
    }

}
