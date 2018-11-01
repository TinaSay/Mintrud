<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.09.2017
 * Time: 19:06
 */

declare(strict_types = 1);


namespace app\modules\news\forms;


use app\modules\news\models\News;
use app\modules\tag\models\Relation;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class CreateNewsForm
 * @package app\modules\news\forms
 */
class NewsForm extends Model
{
    const DATE_FORMAT = 'Y-m-d H:i';

    /**
     *
     */
    const ID_FORM = 'news-form';
    /**
     *
     */
    const DELETE_NO = 0;
    /**
     *
     */
    const DELETE_YES = 1;
    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $text;
    /**
     * @var
     */
    public $directory_id;
    /**
     * @var false|string
     */
    public $date;
    /**
     * @var int
     */
    public $hidden;
    /**
     * @var
     */
    public $show_on_main;
    /**
     * @var
     */
    public $show_on_sovet;
    /**
     * @var string
     */
    public $tags = '';

    /**
     * @var string
     */
    public $src;


    public $directions = [];

    /**
     * @var News
     */
    public $model;

    /**
     * CreateNewsForm constructor.
     * @param News $model
     * @param array $config
     */
    public function __construct(News $model = null, array $config = [])
    {
        parent::__construct($config);
        if (is_null($model)) {
            $this->model = new News();
            $this->hidden = News::HIDDEN_YES;
            $this->date = date(static::DATE_FORMAT);
        } else {
            $this->model = $model;
            Relation::populate($model, 'tags');
            $this->title = $model->title;
            $this->text = $model->text;
            $this->directory_id = $model->directory_id;
            $this->date = $model->date;
            $this->hidden = $model->hidden;
            $this->show_on_main = $model->show_on_main;
            $this->show_on_sovet = $model->show_on_sovet;
            $this->directions = ArrayHelper::getColumn($model->directions, 'id');
        }
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'text', 'date', 'directory_id'], 'required'],
            [['directory_id', 'hidden', 'show_on_main', 'show_on_sovet'], 'integer'],
            [['directory_id', 'hidden', 'show_on_main', 'show_on_sovet'], 'filter', 'filter' => 'intval'],
            [['text', 'tags', 'src'], 'string'],
            ['directions', 'each', 'rule' => ['integer']],
            [['title'], 'string', 'max' => 256],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        return array_merge(
            $this->model->attributeLabels(),
            [
                'directions' => 'Деятельность',
            ]
        );
    }
}