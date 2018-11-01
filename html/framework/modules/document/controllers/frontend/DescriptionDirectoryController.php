<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.07.2017
 * Time: 16:40
 */

declare(strict_types = 1);


namespace app\modules\document\controllers\frontend;


use app\modules\directory\models\Directory;
use app\modules\document\models\DescriptionDirectory;
use app\modules\document\models\Direction;
use app\modules\document\models\Document;
use app\modules\document\models\DocumentDirection;
use app\modules\document\models\NewsDirection;
use app\modules\news\models\News;
use app\modules\system\components\frontend\Controller;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;

/**
 * Class DescriptionDirectoryController
 * @package app\modules\document\controllers\frontend
 */
class DescriptionDirectoryController extends Controller
{
    const LIMIT = 30;
    /**
     * @var string
     */
    public $layout = '//description-directory';

    /**
     * @param $directoryId
     * @param $descriptionId
     * @return string
     */
    public function actionView($directoryId, $descriptionId)
    {
        $directory = $this->findDirection((int)$directoryId);

        $description = $this->findDescription((int)$descriptionId);

        $dependency = new TagDependency([
            'tags' => [
                News::class,
                Document::class,
                DocumentDirection::class,
                NewsDirection::class,
                Direction::class,
            ]
        ]);

        return $this->render(
            'view', [
            'directory' => $directory,
            'description' => $description,
            'dependency' => $dependency,
        ]);
    }

    /**
     * @param int $id
     * @return Directory
     * @throws NotFoundHttpException
     */
    public function findDirection(int $id): Directory
    {
        $model = Directory::find()->id($id)->hidden()->one();
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
        return $model;
    }

    /**
     * @param int $id
     * @return DescriptionDirectory
     * @throws NotFoundHttpException
     */
    public function findDescription(int $id): DescriptionDirectory
    {
        $model = DescriptionDirectory::find()->id($id)->one();
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
        return $model;
    }
}