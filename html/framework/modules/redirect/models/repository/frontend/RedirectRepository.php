<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 18:14
 */

// declare(strict_types=1);


namespace app\modules\redirect\models\repository\frontend;


use app\modules\redirect\models\Redirect;
use app\modules\redirect\models\repository\backend\RedirectRepository as RedirectRepositoryBackend;

/**
 * Class RedirectRepository
 * @package app\modules\redirect\models\repository\frontend
 */
class RedirectRepository extends RedirectRepositoryBackend
{

    /**
     * @return array
     */
    public function findAll(): array
    {
        $model = Redirect::find()
            ->hidden()
            ->all();

        return $model;
    }
}