<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 18.07.17
 * Time: 15:33
 */

namespace app\modules\cabinet\services;

use app\modules\cabinet\form\BlindConfigureForm;
use yii\db\ActiveRecordInterface;

/**
 * Class BlindConfigureService
 *
 * @package app\modules\cabinet\services
 */
class BlindConfigureService
{
    /**
     * @var BlindConfigureForm
     */
    private $form;

    /**
     * @var ActiveRecordInterface
     */
    private $model;

    /**
     * BlindConfigureService constructor.
     *
     * @param BlindConfigureForm $form
     * @param ActiveRecordInterface $model
     */
    public function __construct(BlindConfigureForm $form, ActiveRecordInterface $model)
    {
        $this->form = $form;
        $this->model = $model;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        if ($result = $this->form->validate()) {
            $attributes = $this->form->getAttributes();
            $this->model->setAttribute('blind', $attributes);
            $result = $this->model->save();
        }

        return $result;
    }
}
