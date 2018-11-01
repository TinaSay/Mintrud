<?php

namespace app\modules\newsletter\widgets;

//use app\modules\banner\models\Banner;
use app\modules\newsletter\models\Newsletter;
use yii\base\Widget;

//use yii\helpers\Html;

class NewsletterShow extends Widget
{
    public $nameModel;

    public function init()
    {
        parent::init();
    }

    public function run()
    {

        $model = new Newsletter();

        if ($this->nameModel == 'event') {
            $model->isNews = Newsletter::IS_NEWS_NO;
            $model->isEvent = Newsletter::IS_EVENT_YES;
        } else {
            $model->isNews = Newsletter::IS_NEWS_YES;
            $model->isEvent = Newsletter::IS_EVENT_NO;
        }
        $model->time = Newsletter::TIME_DAILY;

        return $this->render('newsletter-block', ['model' => $model]);

    }
}
