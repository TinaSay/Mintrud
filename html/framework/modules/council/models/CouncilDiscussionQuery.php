<?php

namespace app\modules\council\models;

use app\modules\comment\models\Comment;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[CouncilDiscussion]].
 *
 * @see CouncilDiscussion
 */
class CouncilDiscussionQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere([
            CouncilDiscussion::tableName() . '.[[hidden]]' => CouncilDiscussion::HIDDEN_NO,
        ])->andWhere([
            'AND',
            ['<=', CouncilDiscussion::tableName() . '.[[date_begin]]', new Expression('CURDATE()')],
            ['>=', CouncilDiscussion::tableName() . '.[[date_end]]', new Expression('CURDATE()')],
        ]);
    }

    /**
     * select votes stats to discussion
     *
     * @return $this
     */
    public function votes()
    {
        return $this->addSelect([
            'votes_up' => new Expression('COUNT(DISTINCT([[v1]].[[id]]))'),
            'votes_neutral' => new Expression('COUNT(DISTINCT([[v2]].[[id]]))'),
            'votes_down' => new Expression('COUNT(DISTINCT([[v3]].[[id]]))'),
        ])->leftJoin(['v1' => CouncilDiscussionVote::tableName()],
            CouncilDiscussion::tableName() . '.[[id]] = [[v1]].[[council_discussion_id]] AND [[v1]].[[vote]] = ' . CouncilDiscussionVote::VOTE_PLACET)
            ->leftJoin(['v2' => CouncilDiscussionVote::tableName()],
                CouncilDiscussion::tableName() . '.[[id]] = [[v2]].[[council_discussion_id]] AND [[v2]].[[vote]] = ' . CouncilDiscussionVote::VOTE_ABSTAIN)
            ->leftJoin(['v3' => CouncilDiscussionVote::tableName()],
                CouncilDiscussion::tableName() . '.[[id]] = [[v3]].[[council_discussion_id]] AND [[v3]].[[vote]] = ' . CouncilDiscussionVote::VOTE_CONTRA)
            ->groupBy([CouncilDiscussion::tableName() . '.[[id]]']);
    }

    /**
     * select comments stats to discussion
     *
     * @return $this
     */
    public function comments()
    {
        return $this->addSelect([
            'comments' => new Expression('COUNT(DISTINCT(' . Comment::tableName() . '.[[id]]))'),
        ])->joinWith('comments', false)->groupBy([CouncilDiscussion::tableName() . '.[[id]]'])
            ->groupBy([CouncilDiscussion::tableName() . '.[[id]]']);
    }

    /**
     * @inheritdoc
     * @return CouncilDiscussion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CouncilDiscussion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
