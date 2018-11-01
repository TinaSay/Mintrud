<?php

namespace app\modules\rating\widgets;

use app\modules\rating\models\Rating;
use yii\base\Widget;
use Yii;

class RatingWidget extends Widget
{
    /**
     * @var string
     */
    public $module = '';

    /**
     * @var int
     */
    public $recordId = 0;


    /**
     * @var Rating
     */
    private $rating = null;

    /**
     * @var int
     */
    private $avgRating = 0;


    public function init()
    {
        parent::init();

        $condition = ['module' => $this->module, 'record_id' => $this->recordId];

        if (Yii::$app->getUser()->getIsGuest()) {
            $condition['user_ip'] = Yii::$app->request->userIP;
        } else {
            $condition['user_id'] = Yii::$app->getUser()->getId();
        }

        $this->rating = Rating::findOne($condition);
        if ($this->rating === null) {
            $this->rating = new Rating([
                'module' => $this->module,
                'record_id' => $this->recordId,
                'rating' => 1,
            ]);
            $this->avgRating = 0;
        } else {
            $this->avgRating = Rating::getAvgRating($this->module, $this->recordId);
        }


    }

    public function run(): string
    {
        RatingAsset::register($this->view);
        return $this->render('rating', ['rating' => $this->rating, 'avgRating' => $this->avgRating]);
    }
}
