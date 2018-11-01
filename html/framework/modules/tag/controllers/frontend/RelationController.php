<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.07.2017
 * Time: 13:51
 */

declare(strict_types = 1);


namespace app\modules\tag\controllers\frontend;


use app\modules\system\components\frontend\Controller;
use app\modules\tag\models\Relation;
use yii\data\ActiveDataProvider;

/**
 * Class RelationController
 * @package app\modules\tag\controllers\frontend
 */
class RelationController extends Controller
{
    /**
     * Lists all Relation models by tag_id
     * @param int $id
     * @return string
     */
    public function actionIndex(int $id): string
    {
        $query = Relation::find()->tag($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }
}