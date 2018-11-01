<?php

namespace app\modules\ministry\controllers\backend;

use app\modules\ministry\models\Ministry;
use app\modules\system\components\backend\Controller;
use yii;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\modules\ministry\services\MinistryAssignmentService;
use app\modules\ministry\models\MinistryAssignment;

/**
 * ManageController implements the CRUD actions for Ministry model.
 */
class ManageController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render(
            'index',
            [
                'tree' => Ministry::getTree(Yii::$app->language,
                    MinistryAssignment::isAuthor(Yii::$app->getUser()->getId())),
            ]
        );
    }

    /**
     * @param $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render(
            'view',
            [
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * @return string|yii\web\Response
     * @throws yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $depth = 0;
        $parent_id = Yii::$app->request->get('parent_id');

        if ($parent_id == null) {
            $parent_url = '';
        } else {
            $parent_url = '/';
        }

        if (empty($parent_id)) {
            $parent_id = null;
        } else {
            $parent_url = Ministry::find()->where([
                'id' => $parent_id,
            ])->max('url');
            $depth = Ministry::find()->where([
                'id' => $parent_id,
            ])->max('depth');
            $depth++;
            $parent_url .= '/';
        }

        $model = new Ministry([
            'parent_id' => $parent_id,
            'hidden' => Ministry::HIDDEN_YES,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->type != $model::TYPE_MENU) {
                $model->url = $parent_url . $model->url;
            }
            $model->depth = $depth;
            $model->save(false);

            Yii::createObject(MinistryAssignmentService::class)->saveNewRecord(Yii::$app->getUser()->getId(),
                $model->id);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render(
                'create',
                [
                    'model' => $model,
                    'parent_url' => $parent_url,
                ]
            );
        }
    }

    /**
     * @param $id
     *
     * @return string|yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $parent_url = '/';
        $model = $this->findModel($id);

        $result = Yii::createObject(MinistryAssignmentService::class)->checkIfExists(Yii::$app->getUser()->getId(),
            $model->id);

        if ($result == false) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        if ($model->parent && $model->type != Ministry::TYPE_MENU) {
            $parent_url .= $model->parent->url . '/';
            $model->url = preg_replace('#' . $model->parent->url . '\/?#i', '', $model->url);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->parent && $model->type != Ministry::TYPE_MENU) {
                $model->url = $model->parent->url . '/' . $model->url;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render(
            'update',
            [
                'model' => $model,
                'parent_url' => $parent_url,
            ]
        );
    }

    /**
     * @param $id
     *
     * @return yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     *
     * @return yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionActive($id)
    {
        $model = $this->findModel($id);

        $model->hidden = $model->hidden == Ministry::HIDDEN_NO ? Ministry::HIDDEN_YES : Ministry::HIDDEN_NO;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdateAll()
    {
        $position = 0;
        $item = Yii::$app->getRequest()->post('item', []);

        foreach ($item as $id) {
            $model = $this->findModel($id);
            $model->position = ++$position;
            if (!$model->save(false)) {
                print_r($model->getErrors());
                return '';
            }
        }

        return Yii::t('system', 'The position is saved');
    }

    /**
     * Finds the Ministry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Ministry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
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
