<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 12:24
 */

namespace app\modules\favorite\storage;

use app\modules\favorite\forms\frontend\AddForm;
use app\modules\favorite\source\SourceInterface;

/**
 * Interface StorageInterface
 *
 * @package app\modules\favorite\storage
 */
interface StorageInterface
{
    /**
     * @param AddForm $form
     */
    public function push(AddForm $form);

    /**
     * @return array
     */
    public function pull();

    /**
     * @param SourceInterface $source
     *
     * @return bool
     */
    public function exist(SourceInterface $source);

    /**
     * @param string $url
     */
    public function delete($url);
}
