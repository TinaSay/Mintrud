<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 12.12.17
 * Time: 12:20
 */

namespace app\modules\media\widgets;

use app\modules\media\models\Photo;
use app\modules\media\models\PhotoLink;
use app\modules\news\models\News;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\ActiveRecord;

class MediaPhotoNewsWidget extends Widget
{
    /**
     * @var ActiveRecord|News
     */
    public $model;

    /**
     * @var Photo
     */
    public $photo;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->model) {
            throw new InvalidConfigException('Property "model" must be set');
        }

        $this->photo = Photo::find()
            ->innerJoinWith('photoLinks', false)
            ->where([
                PhotoLink::tableName() . '.[[model]]' => $this->model::className(),
                PhotoLink::tableName() . '.[[recordId]]' => $this->model->id,
            ])->one();

        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('news', [
            'model' => $this->model,
            'photo' => $this->photo,
        ]);
    }
}