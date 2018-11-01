<?php

use app\modules\config\models\Config;
use yii\db\Migration;

/**
 * Class m180221_121214_sovet_contact_information
 */
class m180221_121214_sovet_contact_information extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert(
            '{{%config}}',
            [
                'name' => 'sovet_contact_information',
                'label' => 'Контактная информация',
                'value' => '<div class="border-block">
            <h4 class="text-uppercase text-prime pd-bottom-10">Контактная<br> информация</h4>
            <div class="text-black aside__contact-block">
                <div class="pd-bottom-20">
                    <p class="pd-bottom-5 bold">Деятельность Общественного Совета курирует:</p>
                    <p>
                        <span class="bold-md">Воронин Филипп Игоревич</span><br>
                        <span class="text-md">Советник Министра труда и социальной защиты Российской Федерации</span>
                    </p>
                </div>
                <div class="pd-bottom-20">
                    <p class="pd-bottom-5 bold">Контактный телефон:</p>
                    <p class="text-md"><a class="text-black" href="tel:+74959269901">(495) 926-99-01 (доб. 0122)</a><br>
                        отдел по работе с обращениями граждан</p>
                </div>
                <div class="pd-bottom-20">
                    <p class="pd-bottom-5 bold">E-mail:</p>
                    <p class="text-md">VoroninFI@rosmintrud.ru</p>
                </div>
                <div class="pd-bottom-0">
                    <p class="pd-bottom-5 bold">Заседания Общественного Совета проходят по адресу:</p>
                    <p class="text-md">г. Москва, ул. Ильинка, д. 21</p>
                </div>
            </div>
        </div>',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('{{%config}}', ['name' => 'sovet_contact_information']);
    }

}
