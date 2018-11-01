<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 10:22
 */

namespace app\modules\council\controllers\discussion;

use app\modules\comment\models\Comment;
use app\modules\council\models\CouncilDiscussion;
use app\modules\council\models\CouncilDiscussionVote;
use app\modules\council\models\CouncilMeeting;
use Yii;
use yii\caching\TagDependency;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class DefaultController extends \yii\web\Controller
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
            )->orderBy([
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
        if (Yii::$app->request->isAjax) {
            $range_start = \DateTime::createFromFormat('Y-m-d', Yii::$app->request->get('start', ''));
            $range_end = \DateTime::createFromFormat('Y-m-d', Yii::$app->request->get('end', ''));

            $list = [];
            if ($range_start && $range_end) {
                $key = [
                    __CLASS__,
                    __FILE__,
                    __LINE__,
                    $range_start->format('Y-m-d'),
                    $range_end->format('Y-m-d'),
                ];

                $dependency = new TagDependency([
                    'tags' => [
                        CouncilDiscussion::className(),
                        CouncilDiscussionVote::className(),
                        CouncilMeeting::className(),
                    ],
                ]);


                $list = Yii::$app->getCache()->get($key);

                if ($list === false) {
                    $list = [];
                    $query = CouncilDiscussion::find()->where([
                        '[[hidden]]' => CouncilDiscussion::HIDDEN_NO,
                    ])->andWhere([
                        'OR',
                        ['>', '[[date_begin]]', $range_start->format('Y-m-d')],
                        ['>', '[[date_end]]', $range_start->format('Y-m-d')],
                    ])->andWhere([
                        'OR',
                        ['<', '[[date_begin]]', $range_end->format('Y-m-d')],
                        ['<', '[[date_end]]', $range_end->format('Y-m-d')],
                    ])->orderBy([
                        '[[date_begin]]' => SORT_ASC,
                    ]);

                    foreach ($query->asArray()->all() as $key => $event) {
                        array_push($list, [
                            'title' => $event['title'],
                            'start' => $event['date_begin'],
                            'end' => $event['date_end'],
                            'url' => (Yii::$app->get('lk')->getIsGuest() ? false : Url::to([
                                'card',
                                'id' => $event['id'],
                            ])),
                            'allDay' => true,
                            'properties' => [
                                'id' => $event['id'],
                                'type' => 'discussion',
                                'meeting' => ($event['meeting_id'] > 0),
                                'meeting_id' => $event['meeting_id'],
                            ],
                        ]);
                    }

                    $meetings = CouncilMeeting::find()->where([
                        '[[hidden]]' => CouncilMeeting::HIDDEN_NO,
                    ])->andWhere([
                        'AND',
                        ['>', '[[date]]', $range_start->format('Y-m-d')],
                        ['<', '[[date]]', $range_end->format('Y-m-d')],
                    ])->orderBy([
                        '[[date]]' => SORT_ASC,
                    ])->asArray()->all();

                    if ($meetings) {
                        foreach ($meetings as $event) {
                            array_push($list, [
                                'title' => $event['title'],
                                'start' => $event['date'],
                                'end' => $event['date'],
                                'allDay' => true,
                                'properties' => [
                                    'id' => $event['id'],
                                    'type' => 'meeting',
                                ],
                            ]);
                        }
                    }

                    Yii::$app->getCache()->set($key, $list, null, $dependency);
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;

            return $list;
        }

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
     * @return \yii\web\Response
     */
    public function actionVote($id)
    {
        $model = new CouncilDiscussionVote([
            'council_member_id' => Yii::$app->get('lk')->getId(),
            'council_discussion_id' => $id,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
            Yii::$app->session->addFlash('success', 'Ваш голос принят.');
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