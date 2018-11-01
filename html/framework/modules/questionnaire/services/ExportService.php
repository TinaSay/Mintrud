<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 28.06.17
 * Time: 10:40
 */

namespace app\modules\questionnaire\services;


use app\modules\questionnaire\models\Question;
use app\modules\questionnaire\models\Questionnaire;
use app\modules\questionnaire\models\Result;
use PHPExcel_IOFactory;
use PHPExcel_Writer_IWriter;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class ExportService
{
    const EXPORT_PATH = '@app/modules/questionnaire/data/export';

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
     * @param Questionnaire $model
     *
     * @return string
     */
    public function exportExcel(Questionnaire $model)
    {

        $title = 'Результаты анкетирования для ' .
            '"' . $model->title . '"';

        $this->objPHPExcel->getActiveSheet()->setCellValue('A1', $title);

        /** @var Question[] $questions */
        $questions = Question::find()->where([
            'questionnaire_id' => $model->id,
        ])->indexBy('id')->orderBy(['id' => SORT_ASC])->all();


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

        $rowIndex++;
        $total = Result::find()->where([
            'questionnaire_id' => $model->id,
        ])->count();
        $pageSize = 20;

        for ($currentPage = 0; $currentPage < ceil($total / $pageSize); $currentPage++) {
            $results = Result::find()->where([
                Result::tableName() . '.[[questionnaire_id]]' => $model->id,
            ])->joinWith('resultAnswers')
                ->joinWith('resultAnswerTexts')
                ->orderBy([
                    Result::tableName() . '.[[id]]' => SORT_ASC,
                ])->limit($pageSize)
                ->offset($currentPage * $pageSize)
                ->all();

            foreach ($results as $result) {
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $result['created_at'], new \DateTimeZone('UTC'));

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
                if ($result->resultAnswers) {

                    foreach ($result->resultAnswers as $resultAnswer) {
                        $columnIndex = ArrayHelper::getValue($questionIndexes, $resultAnswer->question_id);
                        if ($columnIndex) {
                            $value = $this->objPHPExcel->getActiveSheet()
                                ->getCellByColumnAndRow($columnIndex, $rowIndex)
                                ->getValue();
                            if (isset($resultAnswer->answer)) {
                                $value = ($value > '' ? $value . ', ' : '') . $resultAnswer->answer->title;
                            }

                            $this->objPHPExcel->getActiveSheet()
                                ->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
                        }
                    }
                }
                if ($result->resultAnswerTexts) {
                    foreach ($result->resultAnswerTexts as $resultAnswer) {
                        $value = $resultAnswer->text;
                        $columnIndex = ArrayHelper::getValue($questionIndexes, $resultAnswer->question_id);
                        if ($columnIndex) {
                            $this->objPHPExcel->getActiveSheet()
                                ->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
                        }
                    }
                }

                $rowIndex++;
            }
            unset($questionnaire);
            unset($answers);
        }


        $exportPath = $this->report_path . 'questionnaire_result_' . date('Y-m-d_H-i') . $this->tmpFileExtension;
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