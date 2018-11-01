<?php

use yii\db\Migration;

class m170712_105539_type_document_add_data extends Migration
{
    public function safeUp()
    {
        $data = [
            'Административный регламент',
            'Аналитический обзор',
            'Доклад',
            'Информационное письмо',
            'Информация',
            'Комментарий',
            'Международный акт',
            'Международный договор',
            'Методические указания',
            'Мониторинг',
            'Определение',
            'Отраслевое соглашение',
            'Отчет',
            'Письмо',
            'План',
            'Положение',
            'Поручение',
            'Постановление',
            'Правительственная телеграмма',
            'Приказ',
            'Программа',
            'Проект',
            'Проект приказа',
            'Протокол',
            'Разъяснения',
            'Распоряжение',
            'Рекомендации',
            'Решение',
            'Соглашение',
            'Справка',
            'Статистика',
            'Телеграмма',
            'Уведомление',
            'Указ',
            'Федеральный закон',
            'Форма',
        ];

        foreach ($data as $datum) {
            $this->insert(
                '{{%type_document}}', [
                'title' => $datum,
                'created_at' => new \yii\db\Expression('NOW()'),
                'updated_at' => new \yii\db\Expression('NOW()')
            ]);
        }
    }

    public function safeDown()
    {
        $this->truncateTable('{{%type_document}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170712_105539_type_document_add_data cannot be reverted.\n";

        return false;
    }
    */
}
