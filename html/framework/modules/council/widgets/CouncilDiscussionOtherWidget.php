<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 06.07.17
 * Time: 17:10
 */

namespace app\modules\council\widgets;

use app\modules\council\models\CouncilDiscussion;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\caching\TagDependency;

class CouncilDiscussionOtherWidget extends Widget
{

    /**
     * @var CouncilDiscussion
     */
    public $model;

    /**
     * @var CouncilDiscussion[]
     */
    protected $list = [];

    /**
     *
     */
    public function init()
    {

        if (!$this->model) {
            throw new InvalidConfigException('The "model" property must be set.');
        }

        $key = [
            __CLASS__,
            __FILE__,
            __LINE__,
        ];

        $dependency = new TagDependency([
            'tags' => [
                CouncilDiscussion::className(),
            ],
        ]);
        $this->list = Yii::$app->getCache()->get($key);

        if ($this->list === false) {

            $this->list = CouncilDiscussion::find()->active()
                ->andWhere([
                    '!=', 'id', $this->model->id,
                ])->limit(6)->asArray()->all();

            Yii::$app->getCache()->set($key, $this->list, null, $dependency);
        }

        parent::init();
    }

    public function run()
    {
        return $this->render('discussions', ['list' => $this->list]);
    }
}