<?php

use app\modules\document\models\Document;
use app\modules\ministry\models\Ministry;
use app\modules\news\models\News;
use app\modules\page\models\Structure;
use app\modules\questionnaire\models\Questionnaire;
use yii\db\Migration;

/**
 * Class m171103_124128_clean
 */
class m171103_124128_clean extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->cleanDocument();
        $this->cleanMinistry();
        $this->cleanNews();
        $this->cleanQuestionnaire();
        $this->cleanStructure();
    }

    protected function cleanDocument()
    {
        $table = Document::tableName();
        $this->clean($table, 'text');
    }

    protected function cleanMinistry()
    {
        $table = Ministry::tableName();
        $this->clean($table, 'text');
    }

    protected function cleanNews()
    {
        $table = News::tableName();
        $this->clean($table, 'text');
    }

    protected function cleanQuestionnaire()
    {
        $table = Questionnaire::tableName();
        $this->clean($table, 'description');
    }

    protected function cleanStructure()
    {
        $table = Structure::tableName();
        $this->clean($table, 'text');
    }

    /**
     * @param string $table
     * @param string $field
     */
    protected function clean($table, $field)
    {
        $this->getDb()->createCommand(
            'UPDATE ' . $table . ' SET `' . $field . '` = REPLACE(`' . $field . '`, \'' . $this->phrase() . '\', \'\') WHERE `' . $field . '` LIKE \'' . $this->phraseLike() . '\''
        )->execute();
    }

    /**
     * @return string
     */
    protected function phrase()
    {
        return 'http://mintrud.dev-vps.ru';
    }

    /**
     * @return string
     */
    protected function phraseLike()
    {
        return '%' . $this->phrase() . '%';
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
