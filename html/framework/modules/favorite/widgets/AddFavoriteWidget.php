<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 14:48
 */

namespace app\modules\favorite\widgets;

use app\modules\favorite\forms\frontend\AddForm;
use app\modules\favorite\services\frontend\ExistService;
use app\modules\favorite\source\ModelSourceInterface;
use app\modules\favorite\source\SourceInterface;
use Yii;
use yii\base\Widget;

/**
 * Class AddFavoriteWidget
 *
 * @package app\modules\favorite\widgets
 */
class AddFavoriteWidget extends Widget
{
    /**
     * @var ModelSourceInterface
     */
    public $model;

    public $addView = 'addFavorite';
    public $existView = 'existFavorite';
    /**
     * @return bool
     */
    public function beforeRun()
    {
        if (!parent::beforeRun()) {
            return false;
        }

        return !Yii::$app->getUser()->getIsGuest();
    }

    /**
     * @return string
     */
    public function run()
    {
        /** @var SourceInterface $source */
        $source = Yii::createObject(SourceInterface::class, [$this->model]);
        $exist = Yii::createObject(ExistService::class, [$source])->execute();

        if ($exist === true) {
            $form = Yii::createObject([
                'class' => AddForm::class,
                'title' => $source->getTitle(),
                'url' => $source->getUrl(),
            ]);
            return $this->render($this->existView, [
                'model' => $source,
                'form' => $form
            ]);
        } else {
            $form = Yii::createObject([
                'class' => AddForm::class,
                'title' => $source->getTitle(),
                'url' => $source->getUrl(),
            ]);

            return $this->render($this->addView, [
                'form' => $form,
            ]);
        }
    }
}
