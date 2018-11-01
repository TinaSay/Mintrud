<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 01.04.18
 * Time: 18:38
 */

namespace app\themes\paperDashboard\widgets\menu;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Widget
 *
 * @package app\themes\paperDashboard\widgets\menu
 */
abstract class Widget extends \yii\base\Widget
{
    /**
     * @var array
     */
    protected static $access = [];

    public function init()
    {
        parent::init();

        if (self::$access == []) {
            self::$access = Yii::$app->getAuthManager()->getPermissionsByUser(
                Yii::$app->getUser()->getIdentity()->getId()
            );
        }
    }

    /**
     * @param array $item
     *
     * @return bool
     */
    protected function checkAccess(array $item): bool
    {
        $url = ArrayHelper::getValue($item, ['url', 0]);

        $filter = array_filter(self::$access, function ($permission) use ($url) {
            $url = trim($url, '/');
            if (count(explode('/', $url)) > 2) {
                return $url === $permission;
            }

            return fnmatch($url . '/*', $permission);
        }, ARRAY_FILTER_USE_KEY);

        return $filter == [] ? false : true;
    }

    /**
     * @param array $items
     *
     * @return bool
     */
    protected function childCheckAccess(array $items): bool
    {
        foreach ($items as $item) {
            if (isset($item['items']) && is_array($item['items'])) {
                if ($this->childCheckAccess($item['items'])) {
                    return true;
                }
            } else {
                if ($this->checkAccess($item)) {
                    return true;
                }
            }
        }

        return false;
    }
}
