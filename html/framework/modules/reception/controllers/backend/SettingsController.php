<?php

namespace app\modules\reception\controllers\backend;

use app\modules\config\helpers\Config as ConfigHelper;
use app\modules\config\models\Config;
use app\modules\reception\form\SettingsForm;
use app\modules\system\components\backend\Controller;
use Yii;

/**
 * ConfigController implements the CRUD actions for CouncilDiscussion model.
 */
class SettingsController extends Controller
{
    /**
     * Lists all CouncilDiscussion models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $settingsForm = new SettingsForm();
        if (ConfigHelper::has('appeal_text_before')) {
            $settingsForm->setAttributes([
                'textBefore' => ConfigHelper::getValue('appeal_text_before'),
                'textRight' => ConfigHelper::getValue('appeal_text_right'),
                'debug' => (int)ConfigHelper::getValue('appeal_debug'),
            ]);
        }
        if ($settingsForm->load(Yii::$app->request->post()) && $settingsForm->validate()) {
            $config_text_before = Config::find()->where(['name' => 'appeal_text_before'])->one();
            $config_text_right = Config::find()->where(['name' => 'appeal_text_right'])->one();
            $config_debug = Config::find()->where(['name' => 'appeal_debug'])->one();
            if (!$config_text_before) {
                (new Config([
                    'name' => 'appeal_text_before',
                    'label' => 'Текст перед формой обращения',
                    'value' => $settingsForm->textBefore,
                ]))->save();
                (new Config([
                    'name' => 'appeal_text_right',
                    'label' => 'Текст c контактами для формы обращений',
                    'value' => $settingsForm->textRight,
                ]))->save();
            } else {
                $config_text_before->setAttribute('value', $settingsForm->textBefore);
                $config_text_before->save();
                $config_text_right->setAttribute('value', $settingsForm->textRight);
                $config_text_right->save();
                $config_debug->setAttribute('value', (string)$settingsForm->debug);
                $config_debug->save();
            }
            Yii::$app->session->addFlash('success', 'Настройки сохранены');

            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'settingsForm' => $settingsForm,
        ]);
    }


}
