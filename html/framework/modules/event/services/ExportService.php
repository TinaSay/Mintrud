<?php


namespace app\modules\event\services;


use app\modules\event\models\Accreditation;
use app\modules\event\models\Event;
use PHPExcel_IOFactory;
use Yii;
use yii\helpers\FileHelper;

class ExportService
{
    const EXPORT_PATH = '@runtime/event';

    /**
     * @var \PHPExcel_Writer_IWriter
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
     * @throws \yii\base\InvalidParamException
     * @throws \PHPExcel_Reader_Exception
     */
    public function __construct($templatePath, $writerType)
    {
        $this->report_path = realpath(Yii::getAlias(static::EXPORT_PATH)) . DIRECTORY_SEPARATOR;
        if(!is_dir(Yii::getAlias(static::EXPORT_PATH))) {
            FileHelper::createDirectory(Yii::getAlias(static::EXPORT_PATH));
        }


        $importFileType = PHPExcel_IOFactory::identify($templatePath);
        $objReader = PHPExcel_IOFactory::createReader($importFileType);
        $this->objPHPExcel = $objReader->load($templatePath);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $writerType);

        $this->tmpFileExtension = strrchr($templatePath, '.');
    }

    /**
     * @param $id
     * @throws \yii\base\InvalidParamException
     */
    public function setExport($id)
    {
        $fh = fopen(Yii::getAlias(static::EXPORT_PATH . '/export.id'), 'wb+');
        fwrite($fh, $id);
        fclose($fh);
    }

    /**
     * @param Event $model
     *
     * @return string
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportExcel(Event $model)
    {
        ignore_user_abort(true);
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);
        set_time_limit(600);

        $title = 'Результаты аккредитации для ' .
            '"' . $model->title . '"';

        $this->objPHPExcel->getActiveSheet()->setCellValue('A1', $title);

        /** @var Accreditation[] $accreditations */
        $accreditations = Accreditation::find()->where([
            'event_id' => $model->id,
        ])->indexBy('id')->orderBy(['id' => SORT_ASC])->all();


        $rowIndex = 2;
        $columnIndex = 0;
        // date column
        $this->objPHPExcel->getActiveSheet()
            ->setCellValueByColumnAndRow($columnIndex, $rowIndex, 'Дата');
        $this->objPHPExcel->getActiveSheet()
            ->getColumnDimensionByColumn($columnIndex)->setWidth(15);
        $columnIndex++;
        $accreditation = new Accreditation();


        // header row
        foreach (Accreditation::getExportRows() as $row) {
            $this->objPHPExcel->getActiveSheet()
                ->setCellValueByColumnAndRow($columnIndex, $rowIndex, $accreditation->getAttributeLabel($row));
            $this->objPHPExcel->getActiveSheet()
                ->getColumnDimensionByColumn($columnIndex)->setWidth(20);
            $columnIndex++;
        }

        $rowIndex++;

        foreach ($accreditations as $accreditation) {
            $columnIndex = 0;
            $time = $accreditation['created_at'] ?? date('Y-m-d H:i:s');
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $time, new \DateTimeZone('UTC'));
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
            $columnIndex++;
            foreach (Accreditation::getExportRows() as $row) {
                $this->objPHPExcel->getActiveSheet()
                    ->setCellValueByColumnAndRow($columnIndex, $rowIndex, $accreditation[$row]);
                $columnIndex++;
            }
            $rowIndex++;
        }


        $exportPath = $this->report_path . 'result_' . date('Y-m-d_H-i') . $this->tmpFileExtension;
        $this->objWriter->save($exportPath);

        unset($this->objPHPExcel, $this->objWriter);

        return $exportPath;
    }

    /**
     * @param $path
     * @param $outputFileName
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public function sendFile($path, $outputFileName)
    {
        $fh = fopen($path, 'rb');

        return Yii::$app->getResponse()->sendStreamAsFile($fh, $outputFileName);
    }
}