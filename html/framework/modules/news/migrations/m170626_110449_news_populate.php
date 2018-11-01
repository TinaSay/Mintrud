<?php

use app\modules\news\components\Populate;
use app\modules\news\components\UploadImage;
use yii\console\Controller;
use yii\db\Migration;

class m170626_110449_news_populate extends Migration
{
    public function up()
    {
        $this->truncateTable('{{%news}}');
        $this->alterColumn('{{%news}}', 'title', $this->string(512)->notNull());
        $file = fopen(__DIR__ . '/data/atlanta_pages.csv', 'rb');
        while ($row = fgetcsv($file)) {
            $populate = new Populate($row, 'news', 4);
            if ($populate->isPage() && $populate->isFitsCategory()) {
                $name = '';
                if ($image = $populate->getImage()) {
                    $upload = new UploadImage($image, 'http://demo.rosmintrud.ru', '@public/news');
                    $name = $upload->getFileName();
                    if (is_null($name)) {
                        $name = '';
                    }
                    $this->db->refresh();
                }
                $exist = (new \yii\db\Query())
                    ->from('{{%news}}')
                    ->andWhere(
                        [
                            'directory_id' => $populate->getDirectoryID(),
                            'url_id' => $populate->getId(),
                        ]
                    )->exists();


                if (!empty($name)) {
                    $command = str_replace('\\', '/', Yii::getAlias('@app/yii') . ' news/thumb/generate -n=' . $name . ' -u=@public/news');
                    exec('bash -c "' . $command . '"', $output, $returnVar);
                    if ($returnVar != Controller::EXIT_CODE_NORMAL) {
                        $name = '';
                    }
                }

                if ($exist) {
                    $this->update(
                        '{{%news}}',
                        [
                            'title' => $populate->getTitle(),
                            'text' => $populate->getText(),
                            'src' => $name,
                            'date' => $populate->getDate(),
                            'created_at' => $populate->getDate(),
                            'updated_at' => $populate->getDate(),
                        ],
                        [
                            'directory_id' => $populate->getDirectoryID(),
                            'url_id' => $populate->getId(),
                        ]
                    );
                } else {
                    $this->insert(
                        '{{%news}}',
                        [
                            'directory_id' => $populate->getDirectoryID(),
                            'url_id' => $populate->getId(),
                            'title' => $populate->getTitle(),
                            'text' => $populate->getText(),
                            'src' => $name,
                            'date' => $populate->getDate(),
                            'created_at' => $populate->getDate(),
                            'updated_at' => $populate->getDate(),
                        ]
                    );
                }

            };
        }
    }

    public function down()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170626_110449_news_populate cannot be reverted.\n";

        return false;
    }
    */
}
