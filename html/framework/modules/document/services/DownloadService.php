<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 06.09.2017
 * Time: 13:39
 */

namespace app\modules\document\services;

use app\modules\document\interfaces\DownloadServiceInterface;
use app\modules\document\models\Document;
use app\modules\document\models\repository\DocumentRepository;
use PhpOffice\PhpWord\Shared\ZipArchive;
use ReflectionClass;
use Yii;
use yii\base\ViewContextInterface;
use yii\helpers\FileHelper;

/**
 * Class DownloadService
 *
 * @package app\modules\document\services
 */
class DownloadService implements ViewContextInterface, DownloadServiceInterface
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * @var
     */
    private $view;

    /**
     * DownloadService constructor.
     *
     * @param string $directory
     * @param DocumentRepository $documentRepository
     */
    public function __construct(string $directory, DocumentRepository $documentRepository)
    {
        $this->directory = rtrim(Yii::getAlias($directory), '/');

        FileHelper::createDirectory($this->directory);

        $this->documentRepository = $documentRepository;
    }

    /**
     * @param int $id
     */
    public function run(int $id)
    {
        $zip = $this->create($id);
        \Yii::$app->response->sendFile($zip, 'archive_' . $id . '.zip',
            ['mimeType' => 'application/zip'])->send();
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function getSize(int $id): int
    {
        $zip = $this->create($id);

        if ($this->hasExist($id)) {
            return filesize($zip);
        } else {
            return 0;
        }
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    private function hasExist($id)
    {
        return file_exists($this->getZipFileName($id));
    }

    /**
     * @param int $id
     * @param bool $force
     *
     * @return string
     */
    public function create(int $id, bool $force = false): string
    {
        if ($this->hasExist($id) && !$force) {
            return $this->getZipFileName($id);
        }

        $zipFileName = $this->getZipFileName($id);

        $zip = new ZipArchive();

        if ($this->hasExist($id)) {
            $zip->open($zipFileName, ZipArchive::OVERWRITE);
        } else {
            $zip->open($zipFileName, ZipArchive::CREATE);
        }

        $model = $this->documentRepository->findOne((int)$id);
        $docxFile = $this->convert($model);

        $zip->addFile($docxFile, basename($docxFile));
        foreach ($model->files as $file) {
            $zip->addFile($file->getSrcPath(), $file->src);
        }

        $zip->close();

        if (file_exists($docxFile)) {
            unlink($docxFile);
        }

        return $zipFileName;
    }

    /**
     * @param Document $model
     *
     * @return string
     */
    protected function convert(Document $model): string
    {
        $basename = 'document_' . $model->id;
        $directory = Yii::getAlias('@runtime/libreoffice');

        FileHelper::createDirectory($directory, 0700, true);

        $html = $this->render('download', ['model' => $model]);
        $htmlFile = $directory . DIRECTORY_SEPARATOR . $basename . '.html';
        $docxFile = $directory . DIRECTORY_SEPARATOR . $basename . '.docx';

        file_put_contents($htmlFile, $html);

        $input = new \krok\libreoffice\Input($htmlFile);
        $format = new \krok\libreoffice\Format(\krok\libreoffice\interfaces\FormatInterface::TEXT_DOCX);
        $output = new \krok\libreoffice\Output($format, $directory);
        $command = new \krok\libreoffice\commands\ConvertCommand($input, $output);

        $binary = 'export HOME=' . $this->getHome() . ' && /opt/libreoffice5.3/program/soffice';
        $convert = new \krok\libreoffice\Wrapper($binary, $this->getHome());
        $convert->execute($command);

        if (file_exists($htmlFile)) {
            unlink($htmlFile);
        }

        return $docxFile;
    }

    /**
     * @return mixed
     */
    private function getHome()
    {
        return sys_get_temp_dir();
    }

    /**
     * @param int $id
     *
     * @return bool|string
     */
    private function getZipFileName($id)
    {
        return Yii::getAlias($this->directory . '/' . $id . '.zip');
    }

    /**
     * @param $view
     * @param array $params
     *
     * @return string
     */
    public function render($view, $params = [])
    {
        return $this->getView()->render($view, $params, $this);
    }

    /**
     * @return \yii\base\View|\yii\web\View
     */
    public function getView()
    {
        if ($this->view === null) {
            $this->view = Yii::$app->getView();
        }

        return $this->view;
    }

    /**
     * @return string
     */
    public function getViewPath()
    {
        $class = new ReflectionClass($this);

        return dirname($class->getFileName()) . DIRECTORY_SEPARATOR . 'views';
    }
}
