<?php

use app\modules\document\models\Document;
use yii\db\Migration;

class m171010_102209_document extends Migration
{
    public function safeUp()
    {
        $list = Document::find()->where([
            'organ_id' => 2,
        ])->all();
        foreach ($list as $row) {
            $row->delete();
        }
    }

    public function safeDown()
    {

    }
}
