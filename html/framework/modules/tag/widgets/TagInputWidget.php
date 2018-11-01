<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.07.2017
 * Time: 13:13
 */

declare(strict_types = 1);


namespace app\modules\tag\widgets;

use app\modules\tag\behaviors\TagBehavior;
use app\modules\tag\interfaces\TagInterface;
use app\themes\paperDashboard\assets\BootstrapSwitchTagsAsset;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Class TagInputWidget
 * @package app\modules\tag\widgets
 */
class TagInputWidget extends InputWidget
{
    /** @var ActiveRecord */
    public $model;

    /**
     * @var
     */
    private $addUrl;

    /**
     * @var
     */
    private $removeUrl;

    /**
     * @var array
     */
    public $event = [
        'beforeItemAdd' => <<<TEXT
            function (event) {
                if ($(this).data('add')) {
                    $.ajax({
                        url: $(this).data('add'),
                        data: {'name': event.item},
                        method: 'post'
                    });
                }
            }
TEXT
        ,
        'beforeItemRemove' => <<<TEXT
            function (event) {
                if ($(this).data('remove')) {
                    $.ajax({
                        url: $(this).data('remove'),
                        data: {'name': event.item},
                        method: 'post'
                    });
                }
            }
TEXT
        ,

    ];

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        if (!($this->model instanceof ActiveRecord)) {
            throw new InvalidConfigException(static::class . '::model must be set as instance of ' . ActiveRecord::class);
        }

        if (!($this->model->getBehavior(TagBehavior::NAME) instanceof TagBehavior)) {
            throw new \RuntimeException('The "TagBehavior" behavior must be set as instance of ' . TagBehavior::class);
        }


        if (!($this->model instanceof TagInterface)) {
            throw new InvalidConfigException('The "model" property must be implement of ' . TagInterface::class);
        }

        if (is_null($this->attribute)) {
            throw new InvalidConfigException(static::class . '::removeUrl must be set');
        }

        if (!$this->model->isNewRecord) {
            $class = $this->model::className();
            if (is_null($this->addUrl)) {
                $this->addUrl = Url::to(['/tag/relation/add-ajax', 'id' => $this->model->id, 'model' => $class], true);
            }

            if (is_null($this->removeUrl)) {
                $this->removeUrl = Url::to(['/tag/relation/remove-ajax', 'id' => $this->model->id, 'model' => $class], true);
            }
        }

        $this->registerOptions();
        $this->registerClientScript();
    }

    /**
     * @return string
     */
    public function run(): string
    {
        return Html::activeTextInput($this->model, $this->attribute, $this->options);
    }

    /**
     *
     */
    public function registerOptions(): void
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        $this->options['data']['add'] = $this->addUrl;
        $this->options['data']['remove'] = $this->removeUrl;
    }

    /**
     *
     */
    public function registerClientScript()
    {
        $js = [];
        foreach ($this->event as $on => $function) {
            $function = new JsExpression($function);
            $js[] = "$('#{$this->options['id']}').on('{$on}', {$function})";
        }
        BootstrapSwitchTagsAsset::register($this->view);

        $typeaheadUrl = Url::to(['/tag/tag/index-json']);

        $this->view->registerJs("
            var tags = new Bloodhound({
              datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
              queryTokenizer: Bloodhound.tokenizers.whitespace,
              prefetch: {
                url: '{$typeaheadUrl}',
                filter: function(list) {
                  return $.map(list, function(tag) {
                    return { name: tag }; });
                },
                cache: false
              }
            });
            
            $('#{$this->options['id']}').tagsinput({
                typeaheadjs: [
                    {
                        minLength: 3
                    },{
                        name: 'tags',
                        displayKey: 'name',
                        valueKey: 'name',
                        source: tags
                    },
                ]
            });
        ");
        $this->view->registerJs(implode(';', $js));
    }
}