<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.08.2017
 * Time: 12:44
 */

// declare(strict_types=1);


namespace app\modules\ministry\controllers\frontend;


use app\modules\ministry\models\Ministry;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function actionFolder($id)
    {
        $view = 'view';

        $model = $this->findModel($id);
        if ($model->language == 'en-US') {
            Yii::$app->language = 'en-US';
            $view = 'view-eng';
        }
        if ($model->layout) {
            $this->layout = $model->layout;
        }
        return $this->render($view, [
            'model' => $model,
            'breadcrumbs' => $this->getBreadCrumbs(),
        ]);
    }


    /**
     * @param $id
     *
     * @return string
     */
    public function actionArticle($id)
    {
        $view = 'view';

        $model = $this->findModel($id);

        if ($model->language == 'en-US') {
            Yii::$app->language = 'en-US';
            $view = 'view-eng';
        }

        if ($model->layout) {
            $this->layout = $model->layout;
        }


        return $this->render($view, [
            'model' => $model,
            'breadcrumbs' => $this->getBreadCrumbs(),
        ]);
    }

    /**
     * @return array
     */
    protected function getBreadCrumbs()
    {
        $navChain = Ministry::find()->asNavChain();
        $breadcrumbs = [];
        foreach ($navChain as $key => $item) {
            array_push($breadcrumbs, [
                'label' => $item['title'],
                'url' => ($key + 1 < count($navChain)) ? '/' . $item['url'] : false,
            ]);
        }
        return $breadcrumbs;
    }

    /**
     * @param int $id
     *
     * @return null|Ministry
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Ministry::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}