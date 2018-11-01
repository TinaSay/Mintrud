<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 27.07.17
 * Time: 13:13
 */

namespace app\modules\atlas\services;

use app\modules\atlas\models\AtlasDirectorySubjectRf;
use app\modules\atlas\models\AtlasStat;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class ImportService
{
    const IMPORT_TYPE_CSV = 'CSV';
    const IMPORT_TYPE_JSON = 'JSON';
    const IMPORT_TYPE_XML = 'XML';

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var string
     */
    protected $data;

    /**
     * @var array
     */
    protected $existsModels = [];

    /**
     * @var int
     */
    protected $directory_rate_id;

    /**
     * @var int
     */
    protected $year;

    /**
     * ImportService constructor.
     *
     * @param string $filename
     * @param string $data
     *
     * @throws Exception
     */
    public function __construct(string $filename = '', string $data = '')
    {
        if (!empty($filename)) {
            if (file_exists($filename)) {
                $this->filename = $filename;
            } else {
                throw new Exception('File "' . $filename . '" does not exists.');
            }
        }
        if (!empty($data)) {
            $this->data = $data;
        }
        if (!$this->filename && !$this->data) {
            throw new Exception('No data for import.');
        }
    }

    /**
     * @param $directory_rate_id int
     * @param $year int
     * @param string $type
     *
     * @return bool
     * @throws Exception
     */
    public function import($directory_rate_id, $year, $type = '')
    {
        $this->directory_rate_id = $directory_rate_id;
        $this->year = $year;
        $this->existsModels = AtlasStat::find()->where([
            'directory_rate_id' => $directory_rate_id,
            'year' => $year,
        ])->all();

        if ($type === 'CSV' || preg_match('#\.csv$#i', $this->filename)) {
            if ($this->filename) {
                $this->data = file_get_contents($this->filename);
            }

            return $this->importCSV();
        }
        throw new Exception('Can\'t import file.');
    }

    /**
     * @return array|bool
     */
    protected function importCSV()
    {
        $created = $updated = $skipped = 0;
        $skipped_str = '';

        $rows = explode(PHP_EOL, $this->data);
        if ($rows) {
            $subjects = ArrayHelper::map(AtlasDirectorySubjectRf::getList(), 'title', 'id');
            $subjects_code = ArrayHelper::map(AtlasDirectorySubjectRf::getList(), 'title', 'code');
            $existStat = ArrayHelper::map($this->existsModels, 'directorySubject.code', 'id');
            $transaction = Yii::$app->getDb()->beginTransaction();
            foreach ($rows as $row) {
                if (empty($row)) {
                    continue;
                }
                $values = explode(';', $row);
                if (count($values) == 2) {
                    list($subject, $value) = $values;

                    $subject = trim($subject);
                    $subject = preg_replace('#([\s]+)#', ' ', $subject);
                    $value = (float)$value;
                    $subject_id = ArrayHelper::getValue($subjects, $subject);
                    $subject_code = ArrayHelper::getValue($subjects_code, $subject);
                    if (!$subject_id) {
                        $subject = preg_replace('#\s?Республика\s?#i', '', $subject);
                        $dict_value = AtlasDirectorySubjectRf::find()
                            ->select(['id', 'code'])
                            ->where(
                                [
                                    'type' => AtlasDirectorySubjectRf::getType(),
                                ]
                            )->andWhere(
                                ['like', 'title', $subject]
                            )->one();
                        if ($dict_value) {
                            $subject_id = $dict_value['id'];
                            $subject_code = $dict_value['code'];
                        }
                        unset($dict_value);
                    }
                    if ($subject_id) {
                        if ($stat_id = ArrayHelper::getValue($existStat, $subject_code)) {
                            $updated++;
                            AtlasStat::updateAll([
                                'value' => $value,
                            ], [
                                'id' => $stat_id,
                            ]);
                        } else {
                            $created++;
                            $state = new AtlasStat([
                                'directory_subject_id' => $subject_id,
                                'directory_rate_id' => $this->directory_rate_id,
                                'year' => $this->year,
                                'value' => $value,
                            ]);

                            $state->save();
                            if ($state->hasErrors()) {
                                print_r($state->getErrors());
                                exit;
                            }
                        }
                    } else {
                        $skipped++;
                        $skipped_str .= ($skipped_str ? ', ' : '') . $subject;
                    }
                }
            }
            $transaction->commit();


            return ['updated' => $updated, 'created' => $created, 'skipped' => $skipped, 'skipped_str' => $skipped_str];
        }

        return false;
    }
}