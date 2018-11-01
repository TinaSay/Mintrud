<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.09.2017
 * Time: 16:30
 */

declare(strict_types = 1);


namespace app\modules\tag\controllers\backend;


use app\modules\system\components\backend\Controller;
use app\modules\tag\models\Relation;
use app\modules\tag\models\Tag;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;


/**
 * Class TagController
 * @package app\modules\tag\controllers\backend
 */
class TagController extends Controller
{
    /**
     * @return array
     */
    public function actionIndexJson(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $models = Tag::find()
            ->select([Tag::tableName() . '.*', 'count' => 'COUNT(' . Relation::tableName() . '.[[id]])'])
            ->leftJoin(
                Relation::tableName(),
                Relation::tableName() . '.[[tag_id]] = ' . Tag::tableName() . '.[[id]]'
            )
            ->groupBy(['id'])
            ->orderBy(['count' => SORT_DESC])
            ->all();

        return ArrayHelper::getColumn($models, 'name');
    }
}