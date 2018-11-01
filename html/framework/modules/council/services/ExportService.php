<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 28.06.17
 * Time: 10:40
 */

namespace app\modules\council\services;


use app\modules\council\models\CouncilDiscussion;
use app\modules\council\models\CouncilDiscussionVote;
use app\modules\council\models\CouncilMember;
use PHPExcel_CachedObjectStorageFactory;
use PHPExcel_IOFactory;
use PHPExcel_Settings;
use PHPExcel_Writer_IWriter;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class ExportService
{
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
     * ExportService constructor.
     * @param string $templatePath
     * @param string $writerType
     */
    public function __construct($templatePath, $writerType)
    {
        PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_to_memcache,
            ['memoryCacheSize' => '64MB']);
        $importFileType = PHPExcel_IOFactory::identify($templatePath);
        $objReader = PHPExcel_IOFactory::createReader($importFileType);
        $this->objPHPExcel = $objReader->load($templatePath);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $writerType);

        $this->tmpFileExtension = strrchr($templatePath, '.');

        return $this;
    }

    /**
     * @param CouncilDiscussion $model
     * @return PHPExcel_Writer_IWriter
     */
    public function exportExcel(CouncilDiscussion $model)
    {

        $date_end = date('Y-m-d') > $model->date_end ? $model->date_end : date('Y-m-d');

        $title = 'Результаты голосования для общественного обсуждения ' .
            '"' . $model->title . '" в период ' .
            Yii::$app->formatter->asDate($model->date_begin, 'dd/MM/YYYY') . ' - ' .
            Yii::$app->formatter->asDate($date_end, 'dd/MM/YYYY');

        $this->objPHPExcel->getActiveSheet()->setCellValue('D1', $title);

        $list = CouncilDiscussionVote::find()
            ->select([
                CouncilDiscussionVote::tableName() . '.*',
                CouncilMember::tableName() . '.[[name]]',
            ])
            ->joinWith('councilMember', false)
            ->where([
                'council_discussion_id' => $model->id,
            ])->andWhere([
                '<=', CouncilDiscussionVote::tableName() . '.[[created_at]]', $date_end . ' 23:59:59',
            ])->asArray()->all();

        $rowIndex = 3;
        foreach ($list as $row) {
            $this->objPHPExcel->getActiveSheet()->setCellValue('A' . $rowIndex, $row['id']);
            $this->objPHPExcel->getActiveSheet()->setCellValue('B' . $rowIndex, $row['council_discussion_id']);
            $this->objPHPExcel->getActiveSheet()->setCellValue('C' . $rowIndex, $row['council_member_id']);
            $this->objPHPExcel->getActiveSheet()->setCellValue('D' . $rowIndex, $row['name']);

            $vote = ArrayHelper::getValue(CouncilDiscussionVote::getVoteStatusList(), $row['vote']);
            $this->objPHPExcel->getActiveSheet()->setCellValue('E' . $rowIndex, $vote);
            $this->objPHPExcel->getActiveSheet()->setCellValue('F' . $rowIndex, $row['comment']);

            $rowIndex++;
        }


        return $this->objWriter;
    }

    /**
     * @param $outputFileName
     * @return Response
     */
    public function sendFile($outputFileName)
    {
        $exportPath = Yii::getAlias('@runtime') .
            '/' . 'council_discussion_vote_result' . $this->tmpFileExtension;
        $this->objWriter->save($exportPath);
        $excelOutput = file_get_contents($exportPath);
        @unlink($exportPath);

        return Yii::$app->getResponse()->sendContentAsFile($excelOutput, $outputFileName);
    }

    /**
     * @return string
     */
    public function getFile()
    {
        $this->tmpFileName = Yii::getAlias('@runtime') .
            '/' . 'council_discussion_vote_result_by_mail' . $this->tmpFileExtension;
        $this->objWriter->save($this->tmpFileName);

        return $this->tmpFileName;
    }

    /**
     * @return void
     */
    public function unlinkFile()
    {
        @unlink($this->tmpFileName);
    }
}