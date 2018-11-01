<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 27.07.17
 * Time: 13:13
 */

namespace app\modules\atlas\services;

use app\modules\atlas\models\AtlasDirectoryRate;
use app\modules\atlas\models\AtlasStat;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use ZipArchive;

class ExportService
{
    const TYPE_CSV = 'CSV';
    const TYPE_JSON = 'JSON';
    const TYPE_XML = 'XML';


    const TMP_DIR = '@runtime/atlas';

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $rates = [];

    /**
     * @var int
     */
    protected $year;

    /**
     * @param int $year
     * @param string $type
     *
     * @return bool
     * @throws Exception
     */
    public function export($year, $type = '')
    {
        $this->cleanUpTmp();

        $this->year = $year;

        $this->rates = AtlasDirectoryRate::find()->select([
            'title',
            'id',
        ])->where([
            'type' => AtlasDirectoryRate::getType(),
        ])->indexBy('id')
            ->column();

        foreach ($this->rates as $id => $rate) {
            $this->data[$id] = AtlasStat::find()->select([
                AtlasStat::tableName() . '.[[directory_subject_id]]',
                AtlasStat::tableName() . '.[[value]]',
                '[[subject]].[[title]] as [[subjectTitle]]',
            ])->where([
                'year' => $year,
                'directory_rate_id' => $id,
            ])->joinWith('directorySubject', true)
                ->orderBy([
                    '[[subjectTitle]]' => SORT_ASC,
                ])
                ->asArray()
                ->all();
        }
        if (!$this->data) {
            return false;
        }
        if ($type === 'CSV') {
            return $this->exportCSV();
        } else {
            throw new Exception('Can\'t export to file.');
        }
    }


    /**
     * @return bool
     * @throws \Exception
     */
    protected function exportCSV()
    {
        $dir = Yii::getAlias(self::TMP_DIR) . '/';

        $files = [];

        foreach ($this->rates as $id => $rate) {
            if (!array_key_exists($id, $this->data) || empty($this->data[$id])) {
                continue;
            }
            $fileName = $dir .
                $this->year . '_' .
                Inflector::slug($rate, '_', true)
                . '.csv';
            $fh = fopen($fileName, 'w');
            fputcsv($fh, ['Субъект РФ', 'Значение показателя']);
            foreach ($this->data[$id] as $stat) {
                fputcsv($fh, [
                    ArrayHelper::getValue($stat, 'subjectTitle', ''),
                    ArrayHelper::getValue($stat, 'value', ''),
                ]);
            }
            fclose($fh);
            array_push($files, $fileName);
        }

        if (empty($files)) {
            return false;
        }

        $archive = $dir . 'atlas_stat_' . $this->year . '.zip';

        $zip = new \ZipArchive();
        if ($zip->open($archive, ZipArchive::CREATE) !== true) {
            throw new \Exception('Cannot create a zip file');
        }

        foreach ($files as $file) {
            $zip->addFile($file, pathinfo($file, PATHINFO_BASENAME));
        }

        $zip->close();

        Yii::$app->response->sendFile($archive)->send();

        return true;
    }

    /**
     * clean up temp files
     */
    public function cleanUpTmp()
    {
        $dir = Yii::getAlias(self::TMP_DIR);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if ($files = glob($dir . '/*.zip')) {
            foreach ($files as $file) {
                @unlink($file);
            }
        }
        if ($files = glob($dir . '/*.csv')) {
            foreach ($files as $file) {
                @unlink($file);
            }
        }
    }
}