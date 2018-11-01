<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 13:02
 */

namespace app\modules\favorite\source;

use Yii;

/**
 * Class NativeSource
 *
 * @package app\modules\favorite\source
 */
class NativeSource implements SourceInterface
{
    /**
     * @var \yii\console\Application|\yii\web\Application
     */
    private $source;

    /**
     * NativeSource constructor.
     *
     * @param ModelSourceInterface|null $model
     */
    public function __construct(?ModelSourceInterface $model)
    {
        $this->source = Yii::$app;
    }

    /**
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->source->controller->view->title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->source->getRequest()->getUrl();
    }
}
