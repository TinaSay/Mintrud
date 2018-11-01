<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 22.07.17
 * Time: 8:40
 */

namespace app\modules\favorite\source;

/**
 * Class ModelSource
 *
 * @package app\modules\favorite\source
 */
class ModelSource implements SourceInterface
{
    /**
     * @var ModelSourceInterface
     */
    private $model;

    /**
     * ModelSource constructor.
     *
     * @param ModelSourceInterface $model
     */
    public function __construct(ModelSourceInterface $model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->model->getTitle();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->model->getUrl();
    }
}
