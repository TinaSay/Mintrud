<?php

namespace app\modules\council\controllers\backend;

use app\modules\config\models\Config;
use app\modules\council\forms\ContactForm;
use app\modules\system\components\backend\Controller;
use app\modules\config\helpers\Config as ConfigHelper;

class ContactController extends Controller
{
    public function actionIndex()
    {
        $contactForm = new ContactForm();
        if (ConfigHelper::has('sovet_contact_information')) {
            $contactForm->contact = ConfigHelper::getValue('sovet_contact_information');
        }
        if ($contactForm->load(\Yii::$app->request->post()) && $contactForm->validate()) {
            $config_sovet_contact_information = Config::find()->where(['name' => 'sovet_contact_information'])->one();
            if (!$config_sovet_contact_information) {
                $config_sovet_contact_information = new Config([
                    'name' => 'sovet_contact_information',
                    'label' => 'Контактная информация',
                    'value' => $contactForm->contact,
                ]);
            }
            $config_sovet_contact_information->setAttribute('value', $contactForm->contact);
            $config_sovet_contact_information->save();
            \Yii::$app->session->addFlash('Настройки сохранены');
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'contactForm' => $contactForm,
        ]);
    }
}