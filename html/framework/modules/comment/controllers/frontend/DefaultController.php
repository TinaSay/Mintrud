<?php

namespace app\modules\comment\controllers\frontend;

use app\modules\comment\models\Comment;
use app\modules\council\models\CouncilDiscussion;
use app\modules\council\models\CouncilMember;
use Yii;
use yii\caching\TagDependency;

/**
 * Default controller for the `comment` module
 */
class DefaultController extends \yii\web\Controller
{
    /**
     * @return yii\web\Response|array
     */
    public function actionCreate()
    {
        $model = new Comment([
            'status' => Comment::STATUS_APPROVED,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->status === $model::STATUS_DEFAULT) {
                Yii::$app->getSession()->addFlash('success', 'Ваш комментарий отправлен на модерацию');
            }
            $model->save();
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

            return ['success' => !$model->hasErrors(), 'record_id' => $model->id];
        }

        return $this->redirect(Yii::$app->getRequest()->getReferrer() . '#comments');
    }

    /**
     * @param $record_id
     * @return string
     */
    public function actionLkCommentList($record_id)
    {
        $this->layout = false;

        $filter = [
            Comment::tableName() . '.[[parent_id]]' => null,
            Comment::tableName() . '.[[language]]' => Yii::$app->language,
            Comment::tableName() . '.[[model]]' => CouncilDiscussion::class,
            Comment::tableName() . '.[[record_id]]' => $record_id,
        ];

        $my = Yii::$app->request->get('my');
        if ($my) {
            $filter[Comment::tableName() . '.[[council_member_id]]'] = Yii::$app->get('lk')->getId();
        }

        $key = [
            __CLASS__,
            __FILE__,
            __LINE__,
            $filter,
        ];

        $dependency = new TagDependency([
            'tags' => [
                CouncilMember::className(),
                Comment::className(),
            ],
        ]);


        $list = Yii::$app->getCache()->get($key);

        if ($list === false) {

            $list = Comment::find()
                ->joinWith('councilMember')
                ->where($filter)->all();

            Yii::$app->getCache()->set($key, $list, null, $dependency);
        }

        return $this->render('@app/modules/comment/widgets/views/_list', [
            'list' => $list,
        ]);
    }
}
