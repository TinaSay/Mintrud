<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 06.07.17
 * Time: 16:01
 */

namespace app\modules\comment\widgets;

use app\modules\comment\models\Comment;
use app\modules\council\models\CouncilDiscussion;
use app\modules\council\models\CouncilMember;
use Yii;
use yii\base\Widget;
use yii\caching\TagDependency;

class LastDiscussionComment extends Widget
{
    /**
     * @var Comment
     */
    protected $comment;

    /**
     * @var CouncilDiscussion
     */
    protected $discussion;

    /**
     *
     */
    public function init()
    {
        $key = [
            __CLASS__,
            __FILE__,
            __LINE__,
        ];

        $dependency = new TagDependency([
            'tags' => [
                CouncilMember::className(),
                Comment::className(),
                CouncilDiscussion::className(),
            ],
        ]);
        $data = Yii::$app->getCache()->get($key);

        if ($data === false) {

            $comment = Comment::find()->where(['model' => CouncilDiscussion::className()])
                ->active()
                ->orderBy(['created_at' => SORT_DESC])->limit(1)->one();

            $discussion = [];
            if ($comment) {
                $discussion = CouncilDiscussion::find()->select(
                    [CouncilDiscussion::tableName() . '.*']
                )->where([
                    CouncilDiscussion::tableName() . '.[[id]]' => $comment->record_id,
                ])->votes()->comments()
                    ->limit(1)
                    ->asArray()->one();
            }
            $data = [$comment, $discussion];

            Yii::$app->getCache()->set($key, $data, null, $dependency);
        }

        list($this->comment, $this->discussion) = $data;

        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('last-discussion-comment', [
            'model' => $this->comment,
            'discussion' => $this->discussion,
        ]);
    }
}