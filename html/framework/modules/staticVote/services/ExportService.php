<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 28.06.17
 * Time: 10:40
 */

namespace app\modules\staticVote\services;


use app\modules\staticVote\models\StaticVoteAnswers;
use app\modules\staticVote\models\StaticVoteQuestion;
use app\modules\staticVote\models\StaticVoteQuestionnaire;
use PHPExcel_IOFactory;
use PHPExcel_Writer_IWriter;
use Yii;
use yii\helpers\Json;
use yii\web\Response;

class ExportService
{
    const EXPORT_PATH = '@app/modules/staticVote/data/export';

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
     * @param StaticVoteQuestionnaire $model
     *
     * @return string
     */
    public function exportExcel(StaticVoteQuestionnaire $model)
    {

        $title = 'Результаты анкетирования для ' .
            '"' . $model->title . '"';

        $this->objPHPExcel->getActiveSheet()->setCellValue('A1', $title);

        /** @var StaticVoteQuestion[] $questions */
        $questions = StaticVoteQuestion::find()->where([
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
            if ($question->input_type == StaticVoteQuestion::INPUT_TYPE_NONE) {
                continue;
            }
            $this->objPHPExcel->getActiveSheet()
                ->setCellValueByColumnAndRow($columnIndex, $rowIndex, $question->question);
            $this->objPHPExcel->getActiveSheet()
                ->getColumnDimensionByColumn($columnIndex)->setWidth(20);
            $questionIndexes[$question->id] = $columnIndex;
            $columnIndex++;
        }

        $rowIndex++;
        $total = StaticVoteAnswers::find()->where([
            'questionnaire_id' => $model->id,
        ])->count();
        $pageSize = 20;

        for ($currentPage = 0; $currentPage < ceil($total / $pageSize); $currentPage++) {
            $answers = StaticVoteAnswers::find()->orderBy([
                'id' => SORT_ASC,
            ])->where([
                'questionnaire_id' => $model->id,
            ])->limit($pageSize)
                ->offset($currentPage * $pageSize)
                ->asArray()
                ->all();

            foreach ($answers as $answer) {
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $answer['created_at'], new \DateTimeZone('UTC'));

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
                    );;
                $questionnaire = Json::decode($answer['questionnaire']);
                foreach ($questionnaire as $question_id => $answer_id) {
                    $value = StaticVoteAnswers::getAnswerValue($questions[$question_id], $answer_id);

                    $this->objPHPExcel->getActiveSheet()
                        ->setCellValueByColumnAndRow($questionIndexes[$question_id], $rowIndex, $value);
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