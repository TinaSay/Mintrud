<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 13.07.17
 * Time: 9:45
 */

namespace app\modules\magic\interfaces;

/**
 * Interface MagicInterface
 *
 * @package app\modules\magic\components
 */
interface MagicInterface
{
    /**
     * @return string
     */
    public function getSrcPath();

    /**
     * @return string
     */
    public function getUploadDir();

    /**
     * @return \yii\db\ActiveRecord|object
     */
    public function getModule();

    /**
     * @return string
     */
    public function getFileName();
}
