<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.08.2017
 * Time: 14:20
 */

declare(strict_types = 1);


namespace app\modules\event\components;


use app\modules\event\models\spider\Spider;
use yii\helpers\Console;

/**
 * Class EventOfSearch
 * @package app\modules\event\components
 */
class EventOfSearch extends BaseEvent
{
    /**
     *
     */
    public function links(): void
    {
        $li = $this->document->find('ul.i-list li.over');
        foreach ($li as $item) {
            $href = pq($item)->find('a')->attr('href');
            Console::stdout($href . PHP_EOL);
            $model = Spider::create(
                $href
            );
            if (!$model->save()) {
                throw new \RuntimeException('Creating error');
            }
        }
    }

    /**
     * @return bool
     */
    public function isPagination(): bool
    {
        $pages = $this->document->find('div.pages');
        return !!$pages->length;
    }

    /**
     * @return bool
     */
    public function isEnd(): bool
    {
        if (!$this->isPagination()) {
            return true;
        }
        $next = $this->document->find('div.pages span.next');
        return !$next->length;
    }
}