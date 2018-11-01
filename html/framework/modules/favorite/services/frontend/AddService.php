<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 15:54
 */

namespace app\modules\favorite\services\frontend;

use app\modules\favorite\forms\frontend\AddForm;
use app\modules\favorite\storage\StorageInterface;

/**
 * Class AddService
 *
 * @package app\modules\favorite\services\frontend
 */
class AddService
{
    /**
     * @var AddForm
     */
    private $form;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * AddService constructor.
     *
     * @param AddForm $form
     * @param StorageInterface $storage
     */
    public function __construct(AddForm $form, StorageInterface $storage)
    {
        $this->form = $form;
        $this->storage = $storage;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        if ($result = $this->form->validate()) {
            $this->storage->push($this->form);
        }

        return $result;
    }
}
