<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 05.07.17
 * Time: 15:18
 */

namespace app\widgets\breadcrumbs;

use Yii;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

class BreadcrumbsWidget extends Breadcrumbs
{

    /**
     * Renders the widget.
     */
    public function run()
    {
        if (empty($this->links)) {
            return;
        }
        $links = [];
        if ($this->homeLink === null) {
            $links[] = $this->renderItem([
                'label' => Yii::t('yii', 'Home'),
                'url' => Yii::$app->homeUrl,
            ], $this->itemTemplate);
        } elseif ($this->homeLink !== false) {
            $links[] = $this->renderItem($this->homeLink, $this->itemTemplate);
        }
        foreach ($this->links as $link) {
            if (!is_array($link)) {
                $link = ['label' => $link];
            }
            $links[] = $this->renderItem($link, isset($link['url']) ? $this->itemTemplate : $this->activeItemTemplate);
        }
        echo Html::tag($this->tag, implode('', $links), $this->options);
    }
}