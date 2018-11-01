<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 24.07.17
 * Time: 15:35
 */

namespace app\modules\favorite\services\frontend;

use app\modules\favorite\source\SourceInterface;
use app\modules\favorite\storage\StorageInterface;

/**
 * Class ExistService
 *
 * @package app\modules\favorite\services\frontend
 */
class ExistService
{
    /**
     * @var SourceInterface
     */
    private $source;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * ExistService constructor.
     *
     * @param SourceInterface $source
     * @param StorageInterface $storage
     */
    public function __construct(SourceInterface $source, StorageInterface $storage)
    {
        $this->source = $source;
        $this->storage = $storage;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        return $this->storage->exist($this->source);
    }
}
