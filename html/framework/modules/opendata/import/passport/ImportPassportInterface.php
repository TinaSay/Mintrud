<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 01.08.17
 * Time: 11:37
 */

namespace app\modules\opendata\import\passport;

use app\modules\opendata\dto\OpendataPassportDTO;

interface ImportPassportInterface
{

    /**
     * @param $data
     *
     * @return OpendataPassportDTO
     */
    public function import($data): OpendataPassportDTO;
}