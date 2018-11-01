<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 28.06.17
 * Time: 10:40
 */

namespace app\modules\testing\services;


use app\modules\testing\models\Testing;
use app\modules\testing\models\TestingQuestion;
use app\modules\testing\models\TestingQuestionAnswer;
use app\modules\testing\models\TestingResult;
use app\modules\testing\models\TestingResultAnswer;
use PHPExcel_IOFactory;
use PHPExcel_Writer_IWriter;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class ExportService
{
    const EXPORT_PATH = '@app/modules/testing/data/export';
    const EXPORT_TEMPLATE_PATH = '@app/modules/testing/data/export.xlsx';

    /**
     * @var PHPExcel_Writer_IWriter
     */
    private $objWriter;

    /**
     * @var \PHPExcel
     */
    private $objPHPExcel;

    /**
     * @var string
     */
    protected $tmpFileExtension = 'xlsx';

    /**
     * @var string
     */
    protected $tmpFileName;

    /**
     * @var string
     */
    public $report_path;

    /**
     * ExportService constructor.
     *
     * @param string $templatePath
     * @param string $writerType
     */
    public function __construct($templatePath, $writerType)
    {
        $this->report_path = realpath(Yii::getAlias(static::EXPORT_PATH)) . "/";

        /*
        \PHPExcel_Settings::setCacheStorageMethod(\PHPExcel_CachedObjectStorageFactory::cache_to_memcache,
            [
                'memcacheServer' => 'memcached',
            ]
        );
        */

        $importFileType = PHPExcel_IOFactory::identify($templatePath);
        $objReader = PHPExcel_IOFactory::createReader($importFileType);
        $this->objPHPExcel = $objReader->load($templatePath);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $writerType);

        $this->tmpFileExtension = strrchr($templatePath, '.');

        return $this;
    }

    /**
     * @param $id
     */
    public function setExport($id)
    {
        $fh = fopen(Yii::getAlias(static::EXPORT_PATH . '/export.id'), 'w+');
        fwrite($fh, $id);
        fclose($fh);
    }

    /**
     * @param Testing $model
     *
     * @return string
     */
    public function exportExcel(Testing $model)
    {

        $title = 'Результаты тестирования для ' .
            '"' . $model->title . '"';

        $this->objPHPExcel->getActiveSheet()->setCellValue('A1', $title);

        /** @var TestingQuestion[] $questions */
        $questions = TestingQuestion::find()->where([
            'testId' => $model->id,
        ])->indexBy('id')->orderBy(['id' => SORT_ASC])->all();

        $answerRightCount = TestingQuestionAnswer::find()->where([
            'testId' => $model->id,
            'right' => TestingQuestionAnswer::RIGHT_YES,
        ])->count();

        $wrongColorStyle = [
            'font' => [
                'color' => ['rgb' => 'FF0000'],
            ],
        ];


        $rowIndex = 2;
        $columnIndex = 0;
        $questionIndexes = [];
        // date column
        $this->objPHPExcel->getActiveSheet()
            ->setCellValueByColumnAndRow($columnIndex, $rowIndex, 'Дата');
        $this->objPHPExcel->getActiveSheet()
            ->getColumnDimensionByColumn($columnIndex)->setWidth(15);
        $columnIndex++;

        // header row
        foreach ($questions as $question) {
            $this->objPHPExcel->getActiveSheet()
                ->setCellValueByColumnAndRow($columnIndex, $rowIndex, $question->title);
            $this->objPHPExcel->getActiveSheet()
                ->getColumnDimensionByColumn($columnIndex)->setWidth(20);
            $questionIndexes[$question->id] = $columnIndex;
            $columnIndex++;
        }
        // add right column counter
        $this->objPHPExcel->getActiveSheet()
            ->setCellValueByColumnAndRow($columnIndex, $rowIndex, 'Правильных ответов');
        $this->objPHPExcel->getActiveSheet()
            ->getColumnDimensionByColumn($columnIndex)->setWidth(20);
        $lastColumnIndex = $columnIndex;

        $rowIndex++;
        $total = TestingResult::find()->where([
            'testId' => $model->id,
        ])->count();
        $pageSize = 20;

        for ($currentPage = 0; $currentPage < ceil($total / $pageSize); $currentPage++) {
            $results = TestingResult::find()->where([
                TestingResult::tableName() . '.[[testId]]' => $model->id,
            ])->joinWith('testingResultAnswers', true)
                ->orderBy([
                    TestingResult::tableName() . '.[[id]]' => SORT_ASC,
                ])->limit($pageSize)
                ->offset($currentPage * $pageSize)
                ->all();

            foreach ($results as $result) {
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $result['createdAt'], new \DateTimeZone('UTC'));

                $date->setTimezone(new \DateTimeZone('Europe/Moscow'));
                $this->objPHPExcel->getActiveSheet()
                    ->setCellValueByColumnAndRow(0, $rowIndex,
                        \PHPExcel_Shared_Date::FormattedPHPToExcel(
                            (int)$date->format('Y'),
                            (int)$date->format('m'),
                            (int)$date->format('d'),
                            (int)$date->format('H'),
                            (int)$date->format('i')
                        )
                    );
                $this->objPHPExcel->getActiveSheet()
                    ->getStyleByColumnAndRow(0, $rowIndex)
                    ->getNumberFormat()
                    ->setFormatCode(
                        'DD.MM.YYYY HH:MM'
                    );
                $right = 0;
                if ($result->testingResultAnswers) {
                    foreach ($result->testingResultAnswers as $resultAnswer) {
                        $columnIndex = ArrayHelper::getValue($questionIndexes, $resultAnswer->testQuestionId);
                        if ($columnIndex) {
                            $value = $this->objPHPExcel->getActiveSheet()
                                ->getCellByColumnAndRow($columnIndex, $rowIndex)
                                ->getValue();
                            if (isset($resultAnswer->testQuestionAnswer)) {
                                $value = ($value > '' ? $value . ', ' : '') . $resultAnswer->testQuestionAnswer->title;
                            }

                            $this->objPHPExcel->getActiveSheet()
                                ->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
                            if ($resultAnswer->right == TestingResultAnswer::RIGHT_YES) {
                                $right++;
                            } else {
                                $this->objPHPExcel->getActiveSheet()
                                    ->getCellByColumnAndRow($columnIndex, $rowIndex)
                                    ->getStyle()
                                    ->applyFromArray($wrongColorStyle);
                            }
                        }
                    }
                }
                $this->objPHPExcel->getActiveSheet()
                    ->setCellValueByColumnAndRow($lastColumnIndex, $rowIndex,
                        $right . ' / ' . $answerRightCount);

                $rowIndex++;
            }
        }


        $exportPath = $this->report_path . 'testing_result_' . date('Y-m-d_H-i') . $this->tmpFileExtension;
        $this->objWriter->save($exportPath);

        unset($this->objPHPExcel);
        unset($this->objWriter);

        return $exportPath;
    }

    /**
     * @param $path
     * @param $outputFileName
     *
     * @return Response
     */
    public function sendFile($path, $outputFileName)
    {
        $fh = fopen($path, 'r');

        return Yii::$app->getResponse()->sendStreamAsFile($fh, $outputFileName);
    }


}