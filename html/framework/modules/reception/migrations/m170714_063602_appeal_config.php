<?php

use app\modules\config\models\Config;
use yii\db\Migration;

class m170714_063602_appeal_config extends Migration
{
    public function safeUp()
    {
        (new Config([
            'name' => 'appeal_text_before',
            'label' => 'Текст перед формой обращения',
            'value' => '<p>Информация о персональных данных авторов обращений, направленных в электронном виде, хранится
                            и обрабатывается с соблюдением требований российского законодательства о персональных
                            данных.</p>
                        <p>Просим Вас внимательно ознакомиться с <a href="#">порядком приема и рассмотрения обращений в
                                общественную приемную.</a></p>
                        <p>Возможно, интересующая Вас информация опубликована в <a href="#">перечне типовых практических
                                вопросов социально-трудовой сферы.</a></p>',
        ]))->save();
        (new Config([
            'name' => 'appeal_text_right',
            'label' => 'Текст c контактами для формы обращений',
            'value' => '<h4 class="text-uppercase text-prime pd-bottom-15">Контактная информация</h4>
                    <div class="text-black aside__contact-block">
                        <div class="pd-bottom-40">
                            <p class="pd-bottom-5 bold">Ответственное лицо:</p>
                            <p>
                                <span class="bold-md">Зайцев Сергей Алексеевич</span><br>
                                <span class="text-md">начальник Отдела по работе<br>
								с обращениями граждан</span></p>
                        </div>
                        <div class="pd-bottom-40">
                            <p class="pd-bottom-5 bold">Справочный телефон/факс:</p>
                            <p class="text-md">(495) 606-15-20<br>
                                отдел по работе с обращениями граждан</p>
                        </div>
                        <div class="pd-bottom-30">
                            <p class="pd-bottom-5 bold">Адрес:</p>
                            <p class="text-md">127994, ГСП-4,<br>
                                г. Москва, ул. Ильинка, д. 21</p>
                        </div>
                    </div>',
        ]))->save();
    }

    public function safeDown()
    {
        Config::deleteAll(['name' => 'appeal_text_before']);
        Config::deleteAll(['name' => 'appeal_text_right']);
    }

}
