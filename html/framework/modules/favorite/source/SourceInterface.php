<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 12:28
 */

namespace app\modules\favorite\source;

/**
 * Interface SourceInterface
 *
 * @package app\modules\favorite\source
 */
interface SourceInterface
{
    /**
     * SourceInterface constructor.
     *
     * @param ModelSourceInterface $model
     */
    public function __construct(ModelSourceInterface $model);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getUrl();
}
