<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 12:25
 */

namespace app\modules\favorite\storage;

use app\modules\favorite\forms\frontend\AddForm;
use app\modules\favorite\models\Favorite;
use app\modules\favorite\source\SourceInterface;
use Yii;

/**
 * Class DatabaseStorage
 *
 * @package app\modules\favorite\storage
 */
class DatabaseStorage implements StorageInterface
{
    /**
     * @var Favorite
     */
    private $model;

    /**
     * DatabaseStorage constructor.
     *
     * @param Favorite $model
     */
    public function __construct(Favorite $model)
    {
        $this->model = $model;
    }

    /**
     * @param AddForm $form
     */
    public function push(AddForm $form)
    {
        $model = $this->model;
        $model->setAttributes($form->getAttributes());

        $model->save();
    }

    /**
     * @return array
     */
    public function pull()
    {
        $model = $this->model;

        return $model::find()->where([
            $model::tableName() . '.[[createdBy]]' => Yii::$app->getUser()->getId(),
        ])->language()->orderBy([$model::tableName() . '.[[createdAt]]' => SORT_DESC])->asArray()->all();
    }

    /**
     * @param SourceInterface $source
     *
     * @return bool
     */
    public function exist(SourceInterface $source)
    {
        $model = $this->model;

        return $model::find()->where([
            'url' => $source->getUrl(),
        ])->exists();
    }

    /**
     * @param string $url
     */
    public function delete($url)
    {
        $model = $this->model;

        $model::deleteAll(['url' => $url]);
    }
}
