<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.08.2017
 * Time: 14:05
 */

declare(strict_types = 1);


namespace app\modules\questionnaire\rules;


use app\modules\document\rules\direction\BaseDirection;

class Questionnaire extends BaseDirection
{
    public function getRoute(): string
    {
        return 'questionnaire/question/view';
    }

}