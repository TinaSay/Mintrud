<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 15.09.17
 * Time: 18:28
 */

namespace app\modules\document\interfaces;

/**
 * Interface DownloadServiceInterface
 *
 * @package app\modules\document\interfaces
 */
interface DownloadServiceInterface
{
    /**
     * @param int $id
     */
    public function run(int $id);

    /**
     * @param int $id
     * @param bool $force
     *
     * @return string
     */
    public function create(int $id, bool $force = false): string;

    /**
     * @param int $id
     *
     * @return int
     */
    public function getSize(int $id): int;
}
