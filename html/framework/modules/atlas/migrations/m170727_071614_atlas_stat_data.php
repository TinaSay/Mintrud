<?php

use app\modules\atlas\models\AtlasDirectoryRate;
use app\modules\atlas\models\AtlasStat;
use app\modules\atlas\services\ImportService;
use yii\db\Migration;

class m170727_071614_atlas_stat_data extends Migration
{
    public function up()
    {
        if (!YII_ENV_TEST) {
            $dir = dirname(__FILE__);
            $files = glob($dir . '/data/*.csv');
            if ($files) {
                foreach ($files as $filename) {
                    if (preg_match("#([a-z]+)_([\d]+)\_?([a-z\_]+)?#i", $filename, $parts)) {

                        $data = file_get_contents($filename);
                        $data = mb_convert_encoding($data, 'UTF-8', 'windows-1251');

                        $service = new ImportService('', $data);
                        $rate_code = $parts[1];
                        $year = $parts[2];
                        $rate_child_code = isset($parts[3]) ? $parts[3] : '';

                        $imported = false;
                        $parent = AtlasDirectoryRate::findOne(['code' => $rate_code]);
                        if ($parent) {
                            $rate = AtlasDirectoryRate::findOne([
                                'parent_id' => $parent->id,
                                'code' => $rate_child_code,
                            ]);
                            if ($rate) {
                                $ret = $service->import($rate->id, $year, ImportService::IMPORT_TYPE_CSV);
                                $imported = $ret['created'] + $ret['updated'];
                            }
                            if ($parent->code == 'total') {
                                $ret = $service->import($parent->id, $year, ImportService::IMPORT_TYPE_CSV);
                                $imported = $ret['created'] + $ret['updated'];
                            }
                        }
                        if (!$imported) {
                            print 'Import failed for ' . basename($filename) . PHP_EOL;
                        } else {
                            print 'Imported ' . basename($filename) . PHP_EOL;
                        }
                    }
                }
            }
        }
    }

    public function down()
    {
        AtlasStat::deleteAll();

        echo "m170727_071614_atlas_stat_data - reverted.\n";
    }
}
