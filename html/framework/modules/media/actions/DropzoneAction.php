<?php

namespace app\modules\media\actions;


class DropzoneAction extends \krok\dropzone\storage\DropzoneAction
{
    public function run()
    {
        sleep(1);
        return parent::run();
    }
}