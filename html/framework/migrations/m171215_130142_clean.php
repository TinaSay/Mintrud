<?php

use app\modules\document\models\Document;
use app\modules\govserv\models\Govserv;
use app\modules\ministry\models\Ministry;
use app\modules\news\models\News;
use yii\db\Migration;

/**
 * Class m171215_130142_clean
 */
class m171215_130142_clean extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->cleanDocument();
        $this->cleanGovserv();
        $this->cleanMinistry();
        $this->cleanNews();
    }

    protected function cleanDocument()
    {
        $table = Document::tableName();
        $this->clean($table, 'text');
    }

    protected function cleanGovserv()
    {
        $table = Govserv::tableName();
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

    /**
     * @param string $table
     * @param string $field
     */
    protected function clean($table, $field)
    {
        $sql = 'UPDATE ' . $table . ' SET `' . $field . '` = REPLACE(`' . $field . '`, \'' . $this->phrase() . '\', \'' . $this->phraseReplace() . '\') WHERE `' . $field . '` LIKE \'' . $this->phraseLike() . '\'';
        $this->getDb()->createCommand(
            $sql
        )->execute();
    }

    /**
     * @return string
     */
    protected function phrase()
    {
        return 'http://rosmintrud.ru';
    }

    /**
     * @return string
     */
    protected function phraseLike()
    {
        return '%' . $this->phrase() . '%';
    }

    protected function phraseReplace()
    {
        return 'https://rosmintrud.ru';
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
