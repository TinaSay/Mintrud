<?php

namespace app\modules\banner\widgets;

use app\modules\banner\models\Banner;
use app\modules\banner\models\BannerCategory;
use yii\db\ActiveQuery;

/**
 * Class BannerWidget
 *
 * @package app\modules\banner\widgets
 */
class BannerWidget extends \yii\base\Widget
{
    /**
     * @return string
     */
    public function run()
    {
        $banners = $this->find();

        return $this->render('index', [
            'banners' => $banners,
        ]);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function find()
    {
        return BannerCategory::find()
            ->joinWith([
                'children' => function (ActiveQuery $query) {
                    return $query->where(['hidden' => Banner::HIDDEN_NO])->orderBy(['position' => SORT_ASC]);
                },
            ])
            ->orderBy([BannerCategory::tableName() . '.[[position]]' => SORT_ASC])
            ->all();
    }
}