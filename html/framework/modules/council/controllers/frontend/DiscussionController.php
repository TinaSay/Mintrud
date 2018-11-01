<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 10:22
 */

namespace app\modules\council\controllers\frontend;

use app\modules\comment\models\Comment;
use app\modules\council\models\CouncilDiscussion;
use app\modules\council\models\CouncilDiscussionVote;
use Yii;
use yii\caching\TagDependency;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DiscussionController extends \yii\web\Controller
{
    /**
     * @var string
     */
    public $layout = '//discussion';

    /**
     * @return string
     */
    public function actionIndex()
    {
        $key = [
            __CLASS__,
            __FILE__,
            __LINE__,
            Yii::$app->getRequest()->getQueryParams(),
        ];

        $dependency = new TagDependency([
            'tags' => [
                CouncilDiscussion::className(),
                CouncilDiscussionVote::className(),
                Comment::className(),
            ],
        ]);


        $data = Yii::$app->getCache()->get($key);

        if ($data === false) {

            $query = CouncilDiscussion::find()->select(
                [CouncilDiscussion::tableName() . '.*']
            )->where([
                CouncilDiscussion::tableName() . '.[[hidden]]' => CouncilDiscussion::HIDDEN_NO,
            ])->orderBy([
                CouncilDiscussion::tableName() . '.[[date_begin]]' => SORT_DESC,
            ])->active();

            $countQuery = clone $query;

            $pagination = new Pagination([
                'totalCount' => $countQuery->count(),
            ]);

            $query->votes()->comments();

            $models = $query->offset($pagination->getOffset())->limit($pagination->getLimit())->asArray()->all();

            $data = [$models, $pagination];

            Yii::$app->getCache()->set($key, $data, null, $dependency);
        }

        list($models, $pagination) = $data;

        return $this->render('index', [
            'list' => $models,
            'pagination' => $pagination,
        ]);

    }

    /**
     * @return array|string
     */
    public function actionCalendar()
    {
        return $this->render('calendar');
    }

    /**
     * @param int $id
     *
     * @return string
     */
    public function actionCard($id)
    {
        $model = $this->findModel($id);
        $voted = true;
        if ($model->vote === $model::VOTE_YES) {
            $voted = CouncilDiscussionVote::find()->where(
                [
                    'council_discussion_id' => $model->id,
                    'council_member_id' => Yii::$app->get('lk')->getId(),
                ]
            )->exists();
        }


        return $this->render(
            'card',
            [
                'model' => $model,
                'voted' => $voted,
            ]
        );
    }

    /**
     * @param $id
     *
     * @return string|array|\yii\web\Response
     */
    public function actionVote($id)
    {
        $model = new CouncilDiscussionVote([
            'council_member_id' => Yii::$app->get('lk')->getId(),
            'council_discussion_id' => $id,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);

            // attach comment to discussion
            (new Comment([
                'model' => CouncilDiscussion::class,
                'record_id' => $id,
                'text' => $model->comment,
                'parent_id' => null,
                'status' => Comment::STATUS_APPROVED,
            ]))->save();

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ['success' => true, 'message' => 'Ваш голос принят.'];
            } else {
                Yii::$app->session->addFlash('success', 'Ваш голос принят.');
            }
        }

        return $this->redirect(['card', 'id' => $id]);
    }

    /**
     * @param integer $id
     *
     * @return null|CouncilDiscussion
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findModel($id)
    {
        /* @var $model CouncilDiscussion */
        if (($model = CouncilDiscussion::find()->where([
                'id' => $id,
                'hidden' => CouncilDiscussion::HIDDEN_NO,
            ])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}