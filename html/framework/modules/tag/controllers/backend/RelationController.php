<?php

declare(strict_types = 1);

namespace app\modules\tag\controllers\backend;

use app\modules\system\components\backend\Controller;
use app\modules\tag\form\TagForm;
use app\modules\tag\interfaces\TagInterface;
use app\modules\tag\models\Relation;
use app\modules\tag\models\search\RelationSearch;
use app\modules\tag\models\Tag;
use RuntimeException;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * RelationController implements the CRUD actions for Relation model.
 */
class RelationController extends Controller
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
     * @param int $id
     * @param string $model
     * @return string
     */
    public function actionIndexModel(int $id, string $model): string
    {
        $searchModel = new RelationSearch();
        $searchModel->record_id = $id;
        $searchModel->model = $model;
        $dataProvider = $searchModel->searchIndexModel(Yii::$app->request->queryParams);

        return $this->render('index-model', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexModelAjax(int $id, string $model): array
    {
        return $this->findModelByIdAndModel($id, $model);
    }


    public function actionAddAjax(int $id, string $model): array
    {
        $response = ['status' => 200];
        Yii::$app->response->format = Response::FORMAT_JSON;
        $name = Yii::$app->request->post('name');
        if (is_null($name)) {
            $response = ['status' => 201, 'message' => 'The "name" post must be set'];
        }
        /** @var string|null $name */
        $tag = Tag::find()->name($name)->one();
        if (is_null($tag)) {
            $tag = Tag::create($name);
            if (!$tag->save()) {
                throw new RuntimeException('Failed to save the object for unknown reason');
            }
        }
        $relation = Relation::create($id, $tag->id, $model);
        if (!$relation->save()) {
            throw new RuntimeException('Failed to save the object for unknown reason');
        }

        return $response;
    }

    public function actionRemoveAjax(int $id, string $model): array
    {
        $response = ['status' => 200];
        Yii::$app->response->format = Response::FORMAT_JSON;

        /** @var string|null $name */
        $name = Yii::$app->request->post('name');
        if (is_null($name)) {
            $response = ['status' => 201, 'message' => 'The "name" post must be set'];
        }

        $relation = Relation::find()
            ->innerJoinWith('tag')
            ->record($id)
            ->model($model)
            ->name($name)
            ->one();

        if (is_null($relation)) {
            $response = ['status' => 202, 'message' => "The '$name' tag does not relation of " . Tag::class];
        }
        if (!$relation->delete()) {
            throw new RuntimeException('Failed to delete the object for unknown reason');
        }

        return $response;
    }

    /**
     * Displays a single Relation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Relation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @param string $model
     * @return mixed
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionCreate(int $id, string $model)
    {
        $tagForm = new TagForm();

        $relation = new Relation([
            'record_id' => $id,
            'model' => $model,
        ]);

        if (!$relation->instanceOf()) {
            throw new NotFoundHttpException('The class ' . $model . ' is not instance of ' . TagInterface::class);
        }

        if ($tagForm->load(Yii::$app->request->post()) && $tagForm->validate()) {
            if (is_null($tag = $tagForm->exist())) {
                $tag = new Tag([
                    'name' => $tagForm->name,
                ]);
                if (!$tag->save()) {
                    throw new Exception('Failed to save the object for unknown reason');
                }
            }
            $relation->tag_id = $tag->id;
            if ($relation->validate()) {
                if (!$relation->save()) {
                    throw new Exception('Failed to save the object for unknown reason');
                }
                return $this->redirect(['index-model', 'id' => $relation->record_id, 'model' => $relation->model]);
            } else {
                $error = $relation->errors;
                foreach ($error as $item) {
                    Yii::$app->session->addFlash('info', $item);
                }
            }

        }

        return $this->render('create', ['tagForm' => $tagForm]);
    }

    /**
     * Deletes an existing Relation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $relation = $this->findModel($id);
        $id = $relation->record_id;
        $model = $relation->model;
        $relation->delete();

        return $this->redirect(['index-model', 'id' => $id, 'model' => $model]);
    }

    public function findModelByIdAndModel(int $id, string $model): array
    {
        $tags = Relation::find()
            ->innerJoinWith('tag')
            ->select([Tag::tableName() . '.[[name]]', Tag::tableName() . '.[[id]]'])
            ->record($id)
            ->model($model)
            ->indexBy('id')
            ->column();

        return $tags;
    }

    /**
     * Finds the Relation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Relation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Relation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
