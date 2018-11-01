<?php

namespace app\modules\council\controllers\backend;

use app\modules\config\helpers\Config as ConfigHelper;
use app\modules\config\models\Config;
use app\modules\council\forms\SettingsForm;
use app\modules\system\components\backend\Controller;
use Yii;

/**
 * ConfigController implements the CRUD actions for CouncilDiscussion model.
 */
class SettingsController extends Controller
{
    /**
     * Lists all CouncilDiscussion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $settingsForm = new SettingsForm([
            'period' => '7',
            'subscribeTime' => '10:00',
        ]);
        if (ConfigHelper::has('council_export_email')) {
            $settingsForm->setAttributes([
                'email' => ConfigHelper::getValue('council_export_email'),
                'period' => ConfigHelper::getValue('council_export_period'),
                'subscribeTime' => ConfigHelper::getValue('council_subscribe_time'),
            ]);
        }
        if ($settingsForm->load(Yii::$app->request->post()) && $settingsForm->validate()) {
            $config_export_email = Config::find()->where(['name' => 'council_export_email'])->one();
            $config_export_period = Config::find()->where(['name' => 'council_export_period'])->one();
            $council_subscribe_time = Config::find()->where(['name' => 'council_subscribe_time'])->one();
            if (!$config_export_email) {
                (new Config([
                    'name' => 'council_export_email',
                    'label' => 'Общественный совет - Редактор',
                    'value' => $settingsForm->email,
                ]))->save();
                (new Config([
                    'name' => 'council_export_period',
                    'label' => 'Общественный совет - период рассылки результатов',
                    'value' => $settingsForm->period,
                ]))->save();
                (new Config([
                    'name' => 'council_subscribe_time',
                    'label' => 'Общественный совет - Время отправки рассылки',
                    'value' => $settingsForm->subscribeTime,
                ]))->save();
            } else {
                $config_export_email->setAttribute('value', $settingsForm->email);
                $config_export_email->save();
                $config_export_period->setAttribute('value', $settingsForm->period);
                $config_export_period->save();
                $council_subscribe_time->setAttribute('value', $settingsForm->subscribeTime);
                $council_subscribe_time->save();
            }
            Yii::$app->session->addFlash('Настройки сохранены');
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'settingsForm' => $settingsForm,
        ]);
    }


}
