<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 24.07.17
 * Time: 14:51
 */

namespace app\modules\favorite\services\frontend;

use app\modules\favorite\storage\StorageInterface;

/**
 * Class DeleteService
 *
 * @package app\modules\favorite\services\frontend
 */
class DeleteService
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * DeleteService constructor.
     *
     * @param string $url
     * @param StorageInterface $storage
     */
    public function __construct($url, StorageInterface $storage)
    {
        $this->url = $url;
        $this->storage = $storage;
    }

    public function execute()
    {
        $this->storage->delete($this->url);
    }
}
