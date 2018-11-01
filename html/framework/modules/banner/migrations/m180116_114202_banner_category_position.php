<?php

use yii\db\Migration;

/**
 * Class m180116_114202_banner_category_position
 */
class m180116_114202_banner_category_position extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%banner_category}}', 'position',
            $this->integer()->notNull()->defaultValue(0)->after('title')
        );

        $this->db->createCommand("UPDATE {{%banner_category}} SET [[position]] = [[id]] * 100 WHERE 1")
            ->execute();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%banner_category}}', 'position');
    }

}
