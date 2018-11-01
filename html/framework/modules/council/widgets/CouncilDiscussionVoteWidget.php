<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 27.06.17
 * Time: 11:00
 */

namespace app\modules\council\widgets;


use app\modules\council\models\CouncilDiscussion;
use app\modules\council\models\CouncilDiscussionVote;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;

class CouncilDiscussionVoteWidget extends Widget
{
    /**
     * @var CouncilDiscussion
     */
    public $model;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->model === null) {
            throw new InvalidConfigException("The 'model' option is required.");
        }

        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        if ($this->model->getAttribute('vote') != CouncilDiscussion::VOTE_YES) {
            return '';
        }

        return $this->render('vote', [
            'discussionModel' => $this->model,
            'model' => new CouncilDiscussionVote([
                'council_member_id' => Yii::$app->get('lk')->getId(),
                'council_discussion_id' => $this->model->id,
            ]),
        ]);
    }

}