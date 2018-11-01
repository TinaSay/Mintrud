<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 31.01.16
 * Time: 21:47
 */

namespace app\widgets\editor;

use Closure;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class EditorWidget extends \yii\widgets\InputWidget
{
    /**
     * @var null
     */
    public $editor = null;

    /**
     * @var string
     */
    public static $editorDefault = 'imperavi';

    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * @var array
     */
    public static $editors = [];

    public function init()
    {
        parent::init();

        self::$editors = array_map(
            function ($editor) {
                return $editor instanceof Closure ? call_user_func($editor) : [
                    'class' => $editor,
                ];
            },
            ArrayHelper::getValue(Yii::$app->params, 'editors', [])
        );

        if ($this->editor === null) {
            $this->editor = self::$editorDefault;
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->registerAsset();
        $editor = ArrayHelper::getValue(self::$editors, $this->editor);

        $widget = Yii::createObject(
            ArrayHelper::merge(
                $editor,
                [
                    'name' => $this->name,
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                ],
                $this->clientOptions
            )
        );

        return $widget->run();
    }

    public function registerAsset()
    {
        EditorWidgetAsset::register($this->getView());
    }

    /**
     * @return array
     */
    public static function asDropDown()
    {
        static $editors = [];

        if ($editors == []) {
            foreach (self::$editors as $name => $editor) {
                $editors = array_merge($editors, [$name => Inflector::titleize($name)]);
            }
        }

        return $editors;
    }
}
