<?php

use yii\db\Migration;

/**
 * Handles adding dynamicId to table `auth`.
 */
class m180207_102645_add_dynamicIp_column_to_auth_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%auth}}', 'dynamicIp', $this->string(15));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%auth}}', 'dynamicIp');
    }
}
