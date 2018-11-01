<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 29.06.2017
 * Time: 17:28
 */

namespace app\modules\directory\components;


use app\modules\directory\models\Directory;

trait BreadcrumbsTrait
{
    /**
     * @param int|null $directory_id
     * @return array
     */
    public function getBreadcrumbs(int $directory_id = null): array
    {
        if (is_null($directory_id)) {
            return [];
        }
        $directories = array_reverse(Directory::find()->getParents($directory_id));
        $links = [];
        foreach ($directories as $directory) {
            $links[] = [
                'label' => $directory['title'],
                'url' => '/' . $directory['url']
            ];
        }
        return $links;
    }
}