<?php

use app\modules\ministry\models\Ministry;
use yii\db\Migration;

/**
 * Class m171209_122612_ministry_faq_hide
 */
class m171209_122612_ministry_faq_hide extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $model = Ministry::findOne([
            'url' => 'reception/help',
        ]);

        if ($model instanceof Ministry) {
            Ministry::updateAll(['hidden' => Ministry::HIDDEN_YES], ['parent_id' => $model->id]);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $model = Ministry::findOne([
            'url' => 'reception/help',
        ]);

        if ($model instanceof Ministry) {
            Ministry::updateAll(['hidden' => Ministry::HIDDEN_NO], ['parent_id' => $model->id]);
        }
    }
}
