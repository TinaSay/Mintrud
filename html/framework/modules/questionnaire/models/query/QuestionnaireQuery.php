<?php

declare(strict_types=1);

namespace app\modules\questionnaire\models\query;

use app\modules\directory\models\query\HiddenTrait;
use app\modules\directory\models\query\LanguageTrait;
use app\modules\questionnaire\models\Questionnaire;

/**
 * This is the ActiveQuery class for [[\app\modules\questionnaire\models\Questionnaire]].
 *
 * @see \app\modules\questionnaire\models\Questionnaire
 */
class QuestionnaireQuery extends \yii\db\ActiveQuery
{
    use LanguageTrait, HiddenTrait {
        HiddenTrait::hidden as directoryHidden;
    }

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\Questionnaire[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\Questionnaire|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $hidden
     * @return QuestionnaireQuery
     */
    public function hidden(int $hidden = Questionnaire::HIDDEN_NO): QuestionnaireQuery
    {
        return $this->andWhere([Questionnaire::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param int $id
     * @return QuestionnaireQuery
     */
    public function id(int $id): self
    {
        return $this->andWhere([Questionnaire::tableName() . '.[[id]]' => $id]);
    }
}
