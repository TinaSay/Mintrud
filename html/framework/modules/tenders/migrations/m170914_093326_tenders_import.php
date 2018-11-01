<?php

use yii\db\Migration;

class m170914_093326_tenders_import extends Migration
{
    public function up()
    {
        if (!YII_ENV_TEST) {
            Yii::$app->runAction('tenders/import');
        }

    }

    public function down()
    {
        $this->truncateTable(\app\modules\tenders\models\Tender::tableName());

        echo "m170914_093326_tenders_import - reverted.\n";
    }

}
