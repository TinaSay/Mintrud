<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 23.07.17
 * Time: 9:02
 */

namespace app\modules\favorite\services\frontend;

use app\modules\favorite\dto\frontend\ListDto;
use app\modules\favorite\storage\StorageInterface;
use Yii;

/**
 * Class ListService
 *
 * @package app\modules\favorite\services\frontend
 */
class ListService
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * ListService constructor.
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return array
     */
    public function execute()
    {
        $list = $this->storage->pull();

        return array_map(function ($row) {
            return Yii::createObject(ListDto::class, [$row]);
        }, $list);
    }
}
