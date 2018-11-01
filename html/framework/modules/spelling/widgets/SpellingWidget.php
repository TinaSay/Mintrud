<?php

namespace app\modules\spelling\widgets;

use app\modules\spelling\models\Spelling;
use yii\base\Widget;

class SpellingWidget extends Widget
{
    public $viewFile = '@app/modules/spelling/widgets/views/modal-form';

    public function run()
    {
        parent::run();
        $model = new Spelling();
        $view = $this->view;
        return $view->render($this->viewFile, ['model' => $model]);
    }

}
