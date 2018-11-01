<?php

use yii\db\Migration;

/**
 * Handles the creation of table `accreditation`.
 */
class m180314_071048_create_accreditation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(
            '{{%accreditation}}',
            [
                'id' => $this->primaryKey(),
                'event_id' => $this->integer()->notNull(),
                'name' => $this->string()->notNull(),
                'surname' => $this->string()->notNull(),
                'middle_name' => $this->string()->notNull(),
                'passport_series' => $this->string()->notNull(),
                'passport_number' => $this->string()->notNull(),
                'passport_burthday' => $this->string()->notNull(),
                'passport_burthplace' => $this->string()->notNull(),
                'passport_issued' => $this->string()->notNull(),
                'org' => $this->string()->notNull(),
                'job' => $this->string()->notNull(),
                'accid' => $this->string()->notNull(),
                'phone' => $this->string()->notNull(),
                'email' => $this->string()->notNull(),
                'base_formation' => $this->string(),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ]
        );
        $this->addForeignKey(
            'fk-accreditation-event_id',
            '{{%accreditation}}',
            'event_id',
            '{{%event}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-accreditation-event_id', '{{%accreditation}}');
        $this->dropTable('{{%accreditation}}');
    }
}
