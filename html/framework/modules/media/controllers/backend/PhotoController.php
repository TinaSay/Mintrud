<?php

namespace app\modules\media\controllers\backend;

use app\modules\media\actions\DropzoneAction;
use app\modules\media\models\Photo;
use app\modules\media\models\search\PhotoSearch;
use app\modules\media\widgets\services\DeleteService;
use app\modules\news\models\News;
use app\modules\system\components\backend\Controller;
use app\modules\tag\models\Relation;
use krok\storage\dto\StorageDto;
use krok\storage\services\FindService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * PhotoController implements the CRUD actions for Photo model.
 */
class PhotoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => DropzoneAction::class,
                'key' => 'gallery',
            ],
        ];
    }

    /**
     * Lists all Photo models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PhotoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Photo model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Photo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Photo([
            'hidden' => Photo::HIDDEN_YES,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->created_at = date('Y-m-d');
            $model->updated_at = date('Y-m-d');
            return $this->render('create', [
                'model' => $model,
                'newsList' => News::asDropDown(),
            ]);
        }
    }

    /**
     * Updates an existing Photo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Relation::populate($model, 'tags');

            return $this->render('update', [
                'model' => $model,
                'newsList' => News::asDropDown(),
            ]);
        }
    }

    /**
     * Deletes an existing Photo model.
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
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDeleteImg($id)
    {
        /** @var StorageDto $dto */
        $dto = Yii::createObject(FindService::class, [['id' => $id]])->one();

        Yii::createObject(DeleteService::class, [$id])->execute();
        Yii::$app->session->setFlash('success', 'Изображение ' . $dto->getHint() . ' удалено');

        $photoId = Yii::$app->request->get('photoId');
        if (empty($photoId)) {
            return $this->redirect(['index']);
        } else {
            return $this->redirect(['update', 'id' => $photoId]);
        }
    }

    /**
     * Finds the Photo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Photo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Photo::findOne($id)) !== null) {
            $model->populateNewsIds();
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
