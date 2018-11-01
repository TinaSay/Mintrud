<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 08.12.17
 * Time: 16:37
 */

namespace app\modules\media\widgets;


class DropzoneWidget extends \krok\dropzone\storage\widgets\DropzoneWidget
{

    /**
     * @return string
     */
    public function run()
    {
        $list = $this->model->{$this->attribute};

        $this->manager->clean($this->key);

        return $this->render('dropZone/index', [
            'model' => $this->model,
            'list' => $list,
            'key' => $this->key,
        ]);
    }

}