<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 05.12.17
 * Time: 15:05
 */

namespace app\modules\faq\controllers\frontend;

use app\modules\faq\models\Faq;
use app\modules\faq\models\FaqCategory;
use app\modules\ministry\models\Ministry;
use app\modules\ministry\rules\MinistryUrlRule;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\caching\TagDependency;

class DefaultController extends Controller
{

    /**
     * @return string
     */
    public function actionIndex()
    {
        $key = [
            __CLASS__,
            __METHOD__,
            Yii::$app->language,
        ];

        $dependency = new TagDependency([
            'tags' => [
                FaqCategory::class,
                Faq::class,
            ],
        ]);
        if (!($data = Yii::$app->cache->get($key))) {
            $list = FaqCategory::find()
                ->joinWith('faqs', true, 'INNER JOIN')
                ->where([
                    FaqCategory::tableName() . '.[[hidden]]' => FaqCategory::HIDDEN_NO,
                    Faq::tableName() . '.[[hidden]]' => Faq::HIDDEN_NO,
                ])->orderBy([
                    FaqCategory::tableName() . '.[[id]]' => SORT_ASC,
                    Faq::tableName() . '.[[id]]' => SORT_ASC,
                ])
                ->asArray()
                ->all();

            $lastUpdated = Faq::find()->max('updatedAt');

            $data = [$list, $lastUpdated];

            Yii::$app->cache->set($key, $data, null, $dependency);
        }

        MinistryUrlRule::$currentRule = Ministry::find()
            ->select(['id', 'url', 'type'])
            ->where([
                'url' => 'reception/help',
            ])
            ->asArray()
            ->one();

        $navChain = Ministry::find()->asNavChain();
        $breadcrumbs = [];
        foreach ($navChain as $key => $item) {
            array_push($breadcrumbs, [
                'label' => $item['title'],
                'url' => ($key + 1 < count($navChain)) ? '/' . $item['url'] : false,
            ]);
        }

        list($list, $lastUpdated) = $data;

        return $this->render('index', [
            'list' => $list,
            'lastUpdated' => $lastUpdated,
            'breadcrumbs' => $breadcrumbs,
        ]);

    }
}