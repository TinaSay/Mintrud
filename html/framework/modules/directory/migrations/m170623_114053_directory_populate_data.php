<?php


use app\modules\directory\components\Populate;
use app\modules\directory\rules\type\TypeInterface;
use yii\db\Migration;

class m170623_114053_directory_populate_data extends Migration
{

    public static $cats;

    public function up()
    {
        $this->alterColumn('{{%directory}}', 'fragment', $this->string(24)->notNull());
        $this->alterColumn('{{%directory}}', 'title', $this->string(128)->notNull());
        if (!YII_ENV_TEST) {
            $file = fopen(__DIR__ . '/data/atlanta_pages.csv', 'rb');
            while ($row = fgetcsv($file)) {
                $populate = new Populate($row, 'news', 4);
                if ($populate->isCategory() && $populate->isFitsCategory()) {
                    $this->insert(
                        '{{%directory}}',
                        [
                            'type' => TypeInterface::TYPE_NEWS,
                            'title' => $populate->getTitle(),
                            'language' => 'ru-RU',
                            'fragment' => $populate->getFragment(),
                            'parent_id' => $populate->getParentId(),
                            'url' => $populate->getUrl(),
                        ]
                    );
                };
            }
        }
    }

    public function down()
    {
        $this->alterColumn('{{%directory}}', 'fragment', $this->string(10)->notNull());
        $this->alterColumn('{{%directory}}', 'title', $this->string(64)->notNull()->defaultValue(''));
    }

}
