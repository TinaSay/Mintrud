<?php

namespace app\modules\page\controllers\frontend;

use app\modules\page\models\Page;
use app\modules\page\models\Structure;
use app\modules\subdivision\models\Subdivision;
use app\modules\system\components\frontend\Controller;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;

class StructureController extends Controller
{
    public function actionRender()
    {
        $key = [
            __CLASS__,
            __FILE__,
            __LINE__,
        ];

        $typesDependency = new TagDependency([
            'tags' => [
                Page::className(),
                Structure::className(),
                Subdivision::className(),
            ],
        ]);

        $data = \Yii::$app->getCache()->getOrSet($key, function () {
            $model = Structure::find()->where(['hidden' => Structure::HIDDEN_NO])->limit(1)->one();
            if (!$model) {
                throw new NotFoundHttpException('Страница не найдена!');
            }
            $subdivisions = Subdivision::find()->hidden()->with('pages')->all();

            return [$model, $subdivisions];
        }, null, $typesDependency);

        list($model, $subdivisions) = $data;

        return $this->render('render', [
            'model' => $model,
            'subdivisions' => $subdivisions,
        ]);
    }

}
