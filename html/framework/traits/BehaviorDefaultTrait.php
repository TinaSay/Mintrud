<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.07.2017
 * Time: 16:29
 */

declare(strict_types = 1);

namespace app\traits;


use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;

/**
 * Class BehaviorDefaultTrait
 * @package app\traits
 */
trait BehaviorDefaultTrait
{

    /**
     * @return array
     */
    public function behaviorsDefault(): array
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
            'CreatedByBehavior' => CreatedByBehavior::class,
            'TagDependencyBehavior' => TagDependencyBehavior::class
        ];
    }
}