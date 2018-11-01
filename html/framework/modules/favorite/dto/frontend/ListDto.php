<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 23.07.17
 * Time: 9:02
 */

namespace app\modules\favorite\dto\frontend;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class ListDto
 *
 * @package app\modules\favorite\dto\frontend
 */
class ListDto
{
    /**
     * @var array
     */
    private $model;

    /**
     * ListDto constructor.
     *
     * @param array $model
     */
    public function __construct(array $model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return ArrayHelper::getValue($this->model, 'title');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return ArrayHelper::getValue($this->model, 'url');
    }

    /**
     * Ссылка на удаление из избранного
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        $url = ArrayHelper::getValue($this->model, 'url');

        return Url::to(['delete', 'url' => $url]);
    }
}
