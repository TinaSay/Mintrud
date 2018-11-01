<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.07.2017
 * Time: 16:07
 */

declare(strict_types = 1);


namespace app\modules\event\models\traits;


use app\modules\event\models\Event;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * Class TagTrait
 * @package app\modules\event\models\traits
 */
trait TagTrait
{
    /**
     * @param int $id
     * @return ActiveQuery
     */
    public static function findModel(int $id): ActiveQuery
    {
        return Event::find()->hidden()->id($id);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function viewUrl(): string
    {
        return Url::to(['/event/event/view', 'id' => $this->id]);
    }
}