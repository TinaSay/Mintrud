<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 12.08.17
 * Time: 12:16
 */

namespace app\modules\ministry\rules;

use app\modules\ministry\models\Ministry;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\UrlRule;

class MinistryUrlRule extends UrlRule
{

    /**
     * @var array
     */
    private static $menu = [];

    /**
     * @var array
     */
    public static $currentRule;

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function menu()
    {
        $key = [
            __CLASS__,
            __METHOD__,
            Yii::$app->language,
        ];

        $dependency = new TagDependency([
            'tags' => [
                Ministry::class,
            ],
        ]);

        if (empty(self::$menu) && (self::$menu = Yii::$app->cache->get($key)) === false) {

            self::$menu = Ministry::find()
                ->select(['id', 'url', 'type'])
                ->active()
                //->showMenu()
                ->notMenu()
                ->indexBy('url')
                ->asArray()
                ->all();

            Yii::$app->cache->set($key, self::$menu, null, $dependency);
        }

        return self::$menu;
    }


    /**
     * create url from string like Url::to(['/ministry/opengov/25'])
     *
     *
     * @param \yii\web\UrlManager $manager
     * @param string $route
     * @param array $params
     *
     * @return bool|string
     */
    public function createUrl($manager, $route, $params)
    {
        $rule = ArrayHelper::getValue(self::menu(), $route);

        if ($rule === null) {
            // todo: check news with urls like /ministry/opengov
            return false;
        } else {
            return $route;
        }
    }

    /**
     * @param \yii\web\UrlManager $manager
     * @param \yii\web\Request $request
     *
     * @return array|bool
     */
    public function parseRequest($manager, $request)
    {
        if (!preg_match($this->pattern, $request->getPathInfo(), $matches)) {
            return false;
        }

        $matches = $this->substitutePlaceholderNames($matches);

        $rule = ArrayHelper::getValue(self::menu(), trim($matches['path'], '/'));

        if ($rule === null) {
            return false;
        }

        self::$currentRule = $rule;

        switch ($rule['type']) {
            case Ministry::TYPE_FOLDER:
                return ['/ministry/default/folder', ['id' => $rule['id']]];
                break;
            case Ministry::TYPE_ARTICLE:
                return ['/ministry/default/article', ['id' => $rule['id']]];
                break;
            default:
                return false;
        }
    }

}