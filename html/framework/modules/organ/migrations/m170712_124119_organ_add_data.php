<?php

use yii\db\Expression;
use yii\db\Migration;

class m170712_124119_organ_add_data extends Migration
{
    public function safeUp()
    {
        $data = [
            'Верховный суд Российской Федерации',
            'Минздравсоцразвития России',
            'Минтруд России',
            'Пенсионный фонд Российской Федерации',
            'Правительство РФ',
            'Президент РФ',
            'Федеральная служба по надзору в сфере защиты прав потребителей и благополучия человека',
            'Федеральная служба по надзору в сфере здравоохранения и социального развития',
            'Федеральная служба по труду и занятости',
            'Федеральное агентство по высокотехнологичной медицинской помощи',
            'Федеральное агентство по здравоохранению и социальному развитию',
            'Федеральное медико-биологическое агентство',
            'Федеральный Фонд обязательного медицинского страхования',
            'Фонд социального страхования Российской Федерации',
        ];

        foreach ($data as $datum) {
            $this->insert(
                '{{%organ}}',
                [
                    'title' => $datum,
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
        echo "m170712_124119_organ_add_data cannot be reverted.\n";

        return false;
    }
    */
}
