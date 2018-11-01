<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.08.2017
 * Time: 15:39
 */

declare(strict_types = 1);

namespace app\modules\document\controllers\frontend;

use app\modules\directory\components\BreadcrumbsTrait;
use app\modules\document\models\Direction;
use app\modules\document\models\Document;
use app\modules\document\models\DocumentDirection;
use app\modules\document\models\NewsDirection;
use app\modules\document\models\repository\DescriptionDirectoryRepository;
use app\modules\document\models\repository\DirectionRepository;
use app\modules\news\models\News;
use yii\base\Module;
use yii\caching\TagDependency;
use yii\web\Controller;

class DirectionController extends Controller
{
    use BreadcrumbsTrait;
    const LIMIT = 30;
    /**
     * @var string
     */
    public $layout = '//description-directory';

    /**
     * @var DirectionRepository
     */
    private $directionRep;
    /**
     * @var DescriptionDirectoryRepository
     */
    private $descriptionDirectoryRep;

    public function __construct(
        $id, Module $module,
        DirectionRepository $directionRep,
        DescriptionDirectoryRepository $descriptionDirectoryRep,
        array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->directionRep = $directionRep;
        $this->descriptionDirectoryRep = $descriptionDirectoryRep;
    }

    /**
     * @param $directoryId
     * @return string
     */
    public function actionView($directoryId): string
    {
        $direction = $this->directionRep->findByDirectory((int)$directoryId);


        $description = $this->descriptionDirectoryRep->findOne($direction->document_description_directory_id);

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
            'view',
            [
                'direction' => $direction,
                'description' => $description,
                'dependency' => $dependency,
            ]
        );
    }
}