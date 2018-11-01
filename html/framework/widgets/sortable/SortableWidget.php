<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 12.11.15
 * Time: 17:09
 */

namespace app\widgets\sortable;

use Closure;
use Yii;
use yii\bootstrap\Html as BootstrapHtml;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\Sortable;

class SortableWidget extends Sortable
{
    /**
     * @var string|Closure
     */
    public $attributeContent = '';

    /**
     * @var string|Closure
     */
    public $viewUrl = 'view';

    /**
     * @var string|Closure
     */
    public $updateUrl = 'update';

    /**
     * @var string|Closure
     */
    public $deleteUrl = 'delete';

    /**
     * @var string|Closure
     */
    public $parentViewUrl = '';

    /**
     * @var string|Closure
     */
    public $parentUpdateUrl = '';

    /**
     * @var string|Closure
     */
    public $parentDeleteUrl = '';

    /**
     * @var array
     */
    public $options = [
        'class' => 'ui-sortable',
    ];

    /**
     * @var array
     */
    public $clientOptions = [
        'axis' => 'y',
        'handle' => '.handle',
    ];

    /**
     * @var array
     */
    public $additionalControls = [];

    /**
     *
     */
    public function init()
    {
        parent::init();
        $this->items = $this->normalizeItems(ArrayHelper::toArray($this->items));
    }

    /**
     * @param array $item
     *
     * @return string
     */
    public function renderItem(array $item)
    {
        $handle = Html::tag('div', BootstrapHtml::icon('resize-vertical'), ['class' => 'handle']);

        if ($this->parentViewUrl !== '') {
            $view = Html::tag(
                'a',
                BootstrapHtml::icon('eye-open'),
                [
                    'href' => ($this->parentViewUrl instanceof Closure ? call_user_func($this->parentViewUrl, $item) : Url::to(
                        [$this->parentViewUrl, 'id' => $item['id']]
                    )),
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                ]
            );
        } elseif ($this->viewUrl !== false) {
            $view = Html::tag(
                'a',
                BootstrapHtml::icon('eye-open'),
                [
                    'href' => ($this->viewUrl instanceof Closure ? call_user_func($this->viewUrl, $item) : Url::to(
                        [$this->viewUrl, 'id' => $item['id']]
                    )),
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                ]
            );
        } else {
            $view = '';
        }
        
        if ($this->parentUpdateUrl !== '') {
            $update = Html::tag(
                'a',
                BootstrapHtml::icon('pencil'),
                [
                    'href' => ($this->parentUpdateUrl instanceof Closure ? call_user_func($this->parentUpdateUrl, $item) : Url::to(
                        [$this->parentUpdateUrl, 'id' => $item['id']]
                    )),
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                ]
            );
        } elseif ($this->updateUrl !== false) {
            $update = Html::tag(
                'a',
                BootstrapHtml::icon('pencil'),
                [
                    'href' => ($this->updateUrl instanceof Closure ? call_user_func($this->updateUrl, $item) : Url::to(
                        [$this->updateUrl, 'id' => $item['id']]
                    )),
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                ]
            );
        } else {
            $update = '';
        }

        if ($this->parentDeleteUrl !== '') {
            $delete = Html::tag(
                'a',
                BootstrapHtml::icon('trash'),
                [
                    'href' => ($this->parentDeleteUrl instanceof Closure ? call_user_func($this->parentDeleteUrl, $item) : Url::to(
                        [$this->parentDeleteUrl, 'id' => $item['id']]
                    )),
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]
            );
        } elseif ($this->deleteUrl !== false) {
            $delete = Html::tag(
                'a',
                BootstrapHtml::icon('trash'),
                [
                    'href' => ($this->deleteUrl instanceof Closure ? call_user_func($this->deleteUrl, $item) : Url::to(
                        [$this->deleteUrl, 'id' => $item['id']]
                    )),
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]
            );
        } else {
            $delete = '';
        }

        $additionalControls = '';
        if ($this->additionalControls) {
            foreach ($this->additionalControls as $key => $control) {
                $additionalControls .= ($control instanceof Closure ? call_user_func($control, $item) :
                        Html::a($control, Url::to([$key]))
                    ) . PHP_EOL;
            }
        }

        $control = Html::tag('div',
            $additionalControls .
            $view . $update . $delete, ['class' => 'control']);

        $content = $this->attributeContent instanceof Closure ? call_user_func(
            $this->attributeContent,
            $item
        ) : $item[$this->attributeContent];

        return Html::tag('div', $handle . $content . $control, ['class' => 'list-group-item']);
    }

    /**
     * @param array $items
     *
     * @return array
     */
    protected function normalizeItems(array $items)
    {
        foreach ($items as &$row) {
            $row['content'] = $this->renderItem($row);
            $row['options'] = ['item-id' => 'item-' . $row['id']];
        }

        return $items;
    }

    protected function registerWidget($name, $id = null)
    {
        parent::registerWidget($name, $id);
        SortableWidgetAsset::register($this->getView());
    }
}
