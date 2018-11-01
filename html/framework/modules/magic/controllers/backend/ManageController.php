<?php

namespace app\modules\magic\controllers\backend;

use app\modules\magic\models\Magic;
use app\modules\magic\widgets\MagicManageWidget;
use app\modules\system\components\backend\Controller;
use Yii;
use yii\imagine\Image;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Class ManageController
 *
 * @package app\modules\magic\controllers
 */
class ManageController extends Controller
{
    public function init()
    {
        parent::init();
        ini_set('max_execution_time', 1 * 60 * 60);
    }

    /**
     * @return string
     */
    public function actionUpload()
    {
        $magic = new Magic(['scenario' => 'many']);
        $magic->load(Yii::$app->request->post());
        $magic->files = UploadedFile::getInstances($magic, 'files');

        if ($magic->validate()) {

            foreach ($magic->files as $file) {

                $model = new Magic(['scenario' => 'one']);

                $model->load(Yii::$app->request->post());
                $model->file = $file;

                $model->setSrc();

                $model->file->saveAs($model->getSrcPath());

                if (preg_match('/image\/(.*)/i', $model->file->type)) {
                    $model->setPreview();
                    Image::thumbnail($model->getSrcPath(), Magic::PREVIEW_WIDTH, Magic::PREVIEW_HEIGHT)
                        ->save(
                            $model->getPreviewPath(),
                            ['quality' => 75]
                        );
                }

                $model->save();
            }
        }

        return $this->display($magic);
    }

    /**
     * @return string
     */
    public function actionUpdate()
    {
        $model = new Magic();
        $list = Yii::$app->request->post($model->formName(), []);

        foreach ($list as $id => $row) {
            $model = $this->findModel($id);
            $model->setAttributes($row);
            $model->save(true, ['label', 'hint', 'position', 'updated_at']);
        }

        return $this->display($model);
    }

    /**
     * @param integer $id
     *
     * @return string
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->display($model);
    }

    /**
     * @param int $id
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);

        Yii::$app->getResponse()->sendFile(
            $model->getSrcPath(),
            str_replace('"', '\'', $model->label) . '.' . $model->extension,
            ['mimeType' => $model->mime]
        )->send();
    }

    /**
     * @param int $id
     *
     * @return \app\modules\magic\models\Magic
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Magic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param \app\modules\magic\models\Magic $model
     *
     * @return string
     */
    protected function display(Magic $model)
    {
        return MagicManageWidget::widget([
            'model' => $model,
            'attribute' => $model::ATTRIBUTE,
            'attributes' => [
                'module' => $model->module,
                'group_id' => $model->group_id,
                'record_id' => $model->record_id,
            ],
        ]);
    }
}
