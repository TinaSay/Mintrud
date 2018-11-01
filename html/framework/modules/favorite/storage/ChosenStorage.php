<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 12:33
 */

namespace app\modules\favorite\storage;

use app\modules\favorite\forms\frontend\AddForm;
use app\modules\favorite\source\SourceInterface;
use Yii;

/**
 * Class ChosenStorage
 *
 * @package app\modules\favorite\storage
 */
class ChosenStorage implements StorageInterface
{
    /**
     * @var StorageInterface[]
     */
    public $list = [];

    /**
     * @param AddForm $form
     */
    public function push(AddForm $form)
    {
        foreach ($this->list as $class) {
            $class = Yii::createObject($class);
            if ($class instanceof StorageInterface) {
                $class->push($form);
            }
        }
    }

    /**
     * @return array
     */
    public function pull()
    {
        $list = [];

        foreach ($this->list as $class) {
            $class = Yii::createObject($class);
            if ($class instanceof StorageInterface) {
                $list = array_merge($list, $class->pull());
            }
        }

        return $list;
    }

    /**
     * @param SourceInterface $source
     *
     * @return bool
     */
    public function exist(SourceInterface $source)
    {
        $result = false;

        foreach ($this->list as $class) {
            $class = Yii::createObject($class);
            if ($class instanceof StorageInterface) {
                if ($class->exist($source)) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * @param string $url
     */
    public function delete($url)
    {
        foreach ($this->list as $class) {
            $class = Yii::createObject($class);
            if ($class instanceof StorageInterface) {
                $class->delete($url);
            }
        }
    }
}
