<?php

namespace app\modules\technicalSupport\widgets;

use app\modules\technicalSupport\models\TechnicalSupport;
use yii\base\Widget;

class TechnicalSupportWidget extends Widget
{
    public $viewFile = '@app/modules/technicalSupport/widgets/views/modal-form';

    public function run()
    {
        parent::run();
        $model = new TechnicalSupport();
        $view = $this->view;
        return $view->render($this->viewFile, ['model' => $model]);
    }

}
