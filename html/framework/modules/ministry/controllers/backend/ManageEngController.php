<?php

namespace app\modules\ministry\controllers\backend;

use app\modules\ministry\models\Ministry;
use app\modules\system\components\backend\Controller;
use yii;
use yii\db\Exception;
use yii\web\NotFoundHttpException;

/**
 * ManageController implements the CRUD actions for Ministry model.
 */
class ManageEngController extends Controller
{
    /**
     * Lists all Ministry models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render(
            'index',
            [
                'tree' => Ministry::getTree('en-US'),
            ]
        );
    }

    /**
     * Displays a single Ministry model.
     *
     * @param integer $id
     *
     * @return mixed
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
     * Creates a new Ministry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $parent_url = '/';
        $parent_id = Yii::$app->request->get('parent_id');
        if (empty($parent_id)) {
            $parent_id = null;
        } else {
            $parent_url = Ministry::find()->where([
                'id' => $parent_id,
            ])->max('url');
            $parent_url .= '/';
        }

        $model = new Ministry([
            'parent_id' => $parent_id,
            'hidden' => Ministry::HIDDEN_YES,
        ]);

        $model->language = 'en-US';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->url = $parent_url . $model->url;
            $model->save(false);

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
     * Updates an existing Ministry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $parent_url = '/';
        $model = $this->findModel($id);
        if ($model->parent) {
            $parent_url .= $model->parent->url . '/';
            $model->url = preg_replace('#' . $model->parent->url . '\/?#i', '', $model->url);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->parent) {
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
     * Deletes an existing Ministry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     *
     * @return \yii\web\Response
     */
    public function actionActive($id)
    {
        $model = $this->findModel($id);

        $model->hidden = $model->hidden == Ministry::HIDDEN_NO ? Ministry::HIDDEN_YES : Ministry::HIDDEN_NO;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionUpdateAll()
    {
        $position = 0;
        $item = Yii::$app->getRequest()->post('item', []);

        foreach ($item as $id) {
            $model = $this->findModel($id);
            $model->position = ++$position;
            if (!$model->save()) {
                throw new Exception('', $model->getErrors());
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
