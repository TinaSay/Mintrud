<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 18:11
 */

// declare(strict_types=1);


namespace app\modules\redirect\rules;


use app\modules\redirect\models\Redirect;
use app\modules\redirect\models\repository\frontend\RedirectRepository;
use Yii;
use yii\base\Object;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlNormalizerRedirectException;
use yii\web\UrlRuleInterface;

/**
 * Class UrlRule
 * @package app\modules\redirect\rules
 */
class UrlRule extends Object implements UrlRuleInterface
{
    /**
     * @var RedirectRepository
     */
    private $redirectRepository;

    /**
     * UrlRules constructor.
     * @param RedirectRepository $redirectRepository
     * @param array $config
     */
    public function __construct(
        RedirectRepository $redirectRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->redirectRepository = $redirectRepository;
    }


    /**
     * @param UrlManager $manager
     * @param Request $request
     * @return false ;
     * @throws UrlNormalizerRedirectException
     */
    public function parseRequest($manager, $request)
    {
        $map = $this->getMap();
        if (isset($map[$request->getPathInfo()])) {
            throw new UrlNormalizerRedirectException($map[$request->getPathInfo()], 302);
        }
        return false;
    }

    /**
     * @param UrlManager $manager
     * @param string $route
     * @param array $params
     * @return false;
     */
    public function createUrl($manager, $route, $params)
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getMap()
    {
        static $map;
        if (is_null($map)) {
            $key = [
                __CLASS__,
                __METHOD__,
                __LINE__
            ];
            Yii::info('map is empty', 'url');
            $dependency = new TagDependency([
                'tags' => [
                    Redirect::class,
                ]
            ]);
            $map = Yii::$app->cache->getOrSet($key, function () {
                $models = $this->redirectRepository->findAll();
                return ArrayHelper::map($models, 'from', 'redirect');
            }, null, $dependency);
        }
        return $map;
    }
}