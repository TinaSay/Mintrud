<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 15.11.15
 * Time: 16:06
 */

namespace app\modules\comment\widgets;

use app\modules\comment\models\Comment;
use app\modules\council\models\CouncilMember;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\caching\TagDependency;

/**
 * Class CommentWidget
 * @package app\modules\comment\widgets
 *
 * usage
 * ```php
 *
 * CommentWidget::widget([
 *      'attributes' => [
 *          'model' => $model::className(),
 *          'record_id' => $model->id,
 *       ],
 *       'showForm' => true,
 *  ]);
 *
 * ```
 */
class CommentWidget extends Widget
{
    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var bool
     */
    public $showForm = true;

    public function init()
    {
        parent::init();

        if ($this->attributes == []) {
            throw new InvalidConfigException('The "attributes" property must be set.');
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $dependency = new TagDependency([
            'tags' => [
                CouncilMember::className(),
                Comment::className(),
            ],
        ]);

        $list = Comment::find()->joinWith('councilMember')->where($this->attributes)->andWhere([
            Comment::tableName() . '.[[parent_id]]' => null,
            Comment::tableName() . '.[[language]]' => Yii::$app->language,
        ])->all();

        return $this->render('index', [
            'list' => $list,
            'model' => new Comment($this->attributes),
            'showForm' => $this->showForm,
            'authorized' => !Yii::$app->get('lk')->getIsGuest(),
            'properties' => [
                'enabled' => !YII_DEBUG,
                'duration' => 1 * 60 * 60,
                'dependency' => $dependency,
                'variations' => [
                    'language' => Yii::$app->language,
                    'attributes' => $this->attributes,
                    'showForm' => $this->showForm,
                    'authorized' => !Yii::$app->get('lk')->getIsGuest(),
                ],
            ],
        ]);
    }
}
