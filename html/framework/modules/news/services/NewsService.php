<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.09.2017
 * Time: 11:06
 */

declare(strict_types=1);


namespace app\modules\news\services;


use app\modules\document\models\repository\DirectionRepository;
use app\modules\news\forms\NewsForm;
use app\modules\news\helpers\File;
use app\modules\news\helpers\Path;
use app\modules\news\models\News;
use app\modules\news\models\repository\NewsRepository;
use DateTime;
use DomainException;

/**
 * Class NewsService
 *
 * @package app\modules\news\services
 */
class NewsService
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;
    /**
     * @var DirectionRepository
     */
    private $directionRepository;

    /**
     * NewsService constructor.
     *
     * @param NewsRepository $newsRepository
     * @param DirectionRepository $directionRepository
     */
    public function __construct(
        NewsRepository $newsRepository,
        DirectionRepository $directionRepository
    ) {
        $this->newsRepository = $newsRepository;
        $this->directionRepository = $directionRepository;
    }

    /**
     * @param NewsForm $form
     *
     * @return News
     */
    public function create(NewsForm $form): News
    {
        $this->validateDate($date = DateTime::createFromFormat(NewsForm::DATE_FORMAT, $form->date));
        $model = News::create(
            $form->directory_id,
            $form->title,
            $form->text,
            $date,
            $form->show_on_main,
            $form->show_on_sovet,
            $form->hidden
        );

        // set scenario for odata
        $model->setScenario(News::SCENARIO_OPEN_DATA);

        $this->updateAttributeSrc($model, $form);
        $model->setTags($form->tags);
        if (is_array($form->directions)) {
            $model->directions = $this->directionRepository->findByIds($form->directions);
        } else {
            $model->directions = [];
        }
        $this->newsRepository->save($model);

        return $model;
    }

    /**
     * @param NewsForm $form
     *
     * @return News
     */
    public function createEnglish(NewsForm $form)
    {
        $this->validateDate($date = DateTime::createFromFormat('Y-m-d', $form->date));
        $model = News::create(
            $form->directory_id,
            $form->title,
            $form->text,
            $date,
            $form->show_on_main,
            $form->hidden
        );
        $this->updateAttributeSrc($model, $form);
        $model->setTags($form->tags);
        $this->newsRepository->save($model);

        return $model;
    }


    /**
     * @param int $id
     * @param NewsForm $form
     *
     * @return News
     */
    public function update(int $id, NewsForm $form): News
    {
        $this->validateDate($date = DateTime::createFromFormat(NewsForm::DATE_FORMAT, $form->date));
        $model = $this->newsRepository->findOne($id);
        $this->newsRepository->notFoundException($model);
        $this->dirtyDirectory($model, $form->directory_id);
        $model->title = $form->title;
        $model->text = $form->text;
        $model->date = $date->format(NewsForm::DATE_FORMAT);
        $model->hidden = $form->hidden;
        $model->show_on_main = $form->show_on_main;
        $model->show_on_sovet = $form->show_on_sovet;
        $this->updateAttributeSrc($model, $form);
        $model->setTags($form->tags);

        // set scenario for odata
        $model->setScenario(News::SCENARIO_OPEN_DATA);

        if (is_array($form->directions)) {
            $model->directions = $this->directionRepository->findByIds($form->directions);
        } else {
            $model->directions = [];
        }

        $this->newsRepository->save($model);

        return $model;
    }

    /**
     * @param int $id
     * @param NewsForm $form
     *
     * @return News
     */
    public function updateEnglish(int $id, NewsForm $form): News
    {
        $this->validateDate($date = DateTime::createFromFormat('Y-m-d', $form->date));
        $model = $this->newsRepository->findOne($id);
        $this->newsRepository->notFoundException($model);
        $this->dirtyDirectory($model, $form->directory_id);
        $model->title = $form->title;
        $model->text = $form->text;
        $model->date = $date->format('Y-m-d');
        $model->hidden = $form->hidden;
        $model->show_on_main = $form->show_on_main;

        $this->updateAttributeSrc($model, $form);
        $model->setTags($form->tags);
        $this->newsRepository->save($model);

        return $model;
    }

    /**
     * @param News $model
     * @param NewsForm $form
     */
    public function updateAttributeSrc(News $model, NewsForm $form): void
    {
        if (empty($form->src)) {
            $model->src = '';
        } else {
            $file = new File(substr(pathinfo($form->src, PATHINFO_BASENAME), strlen(UploadedFileService::PREFIX)));
            $path = new Path(News::UPLOAD_DIRECTORY, '@root', $file);
            if ($path->isExist()) {
                $model->src = $path->getFile()->getName();
            } else {
                throw new DomainException('Нет изображение');
            }
        }
    }


    /**
     * @param News $model
     * @param int $directoryId
     */
    public function dirtyDirectory(News $model, int $directoryId)
    {
        $model->directory_id = $directoryId;
        if ($model->isAttributeChanged('directory_id')) {
            $model->generateUrlId();
        }
    }

    /**
     * @param $dateTime
     */
    public function validateDate($dateTime)
    {
        if ($dateTime === false) {
            throw new DomainException('Некорректная дата');
        }
    }
}