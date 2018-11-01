<?php

namespace app\modules\page\controllers\frontend;

use app\modules\subdivision\models\Subdivision;
use app\modules\page\models\Page;
use app\modules\system\components\frontend\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class PageController
 * @package app\modules\page\controllers\frontend
 */
class PageController extends Controller
{
    /**
     * @param $subdivision
     * @param $page
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionRender($subdivision, $page)
    {
        $model = Page::find()->joinWith('subdivision')
            ->where([
                Page::tableName() . '.[[alias]]' => $page,
                Subdivision::tableName() . '.[[fragment]]' => $subdivision
            ])
            ->limit(1)->one();

        if (!$model) {
            throw new NotFoundHttpException('Страница не найдена!');
        }

        return $this->render('render', [
            'model' => $model,
        ]);
    }
}
