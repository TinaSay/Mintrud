<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.07.2017
 * Time: 13:38
 */

declare(strict_types = 1);


namespace app\modules\tag\controllers\frontend;


use app\modules\system\components\frontend\Controller;
use app\modules\tag\models\Tag;
use yii\data\ActiveDataProvider;

class TagController extends Controller
{
    public $defaultAction = 'index';

    /**
     * List all Tag models
     */
    public function actionIndex(): string
    {
        $query = Tag::find();

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