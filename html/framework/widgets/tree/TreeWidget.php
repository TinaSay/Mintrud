<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 19.10.15
 * Time: 20:09
 */

namespace app\widgets\tree;

use app\widgets\sortable\SortableWidget;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TreeWidget extends SortableWidget
{
    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function renderItems()
    {
        $items = [];
        foreach ($this->items as $item) {
            $options = $this->itemOptions;
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            if (is_array($item)) {
                if (!isset($item['content'])) {
                    throw new InvalidConfigException('The `content` option is required.');
                }

                if (isset($item['children']) && is_array($item['children'])) {

                    $childrenOptions = $this->options;
                    ArrayHelper::remove($childrenOptions, 'id');

                    $children = self::widget(
                        [
                            'items' => $item['children'],
                            'attributeContent' => $this->attributeContent,
                            'viewUrl' => $this->viewUrl,
                            'updateUrl' => $this->updateUrl,
                            'deleteUrl' => $this->deleteUrl,
                            'options' => $childrenOptions,
                            'itemOptions' => $this->itemOptions,
                            'clientOptions' => $this->clientOptions,
                            'clientEvents' => $this->clientEvents,
                            'additionalControls' => $this->additionalControls,
                        ]
                    );
                } else {
                    $children = '';
                }

                $options = array_merge($options, ArrayHelper::getValue($item, 'options', []));
                $tag = ArrayHelper::remove($options, 'tag', $tag);

                $items[] = Html::tag($tag, $this->renderItem($item) . $children, $options);
            } else {
                $items[] = Html::tag($tag, $item, $options);
            }
        }

        return implode(PHP_EOL, $items);
    }
}
