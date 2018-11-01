<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.07.2017
 * Time: 17:15
 */

declare(strict_types = 1);


namespace app\modules\document\components;

use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\document\models\repository\DocumentRepository;
use app\modules\document\models\spider\Spider;
use yii\helpers\Console;

class DocumentOfSearch extends BaseDocument
{

    /**
     *
     */
    public function saveLinks(): void
    {
        $list = $this->document->find('div.dark-box ul.doc-list li');

        foreach ($list as $item) {
            $href = pq($item)->find('a')->attr('href');
            $spider = Spider::create($href);
            if (!$spider) {
                Console::stdout("The $href href is exist" . PHP_EOL);
                continue;
            }
            $path = parse_url($href, PHP_URL_PATH);
            $parts = explode('/', $path);
            $id = array_pop($parts);
            if (!is_numeric($id)) {
                return;
            }
            if (strncmp('0', $id, 1) === 0) {
                continue;
            }
            $path = implode('/', $parts);
            $directoryRepository = new DirectoryRepository();
            $directory = $directoryRepository->findOneByUrl(trim($path, '/'));
            if (is_null($directory)) {
                continue;
            }
            $documentRepository = new DocumentRepository();
            $document = $documentRepository->findOneByUrlDirectory((int)$id, $directory->id);
            if (!is_null($document)) {
                continue;
            }
            $spider->save();
        }
    }

    /**
     * @param string|null $typeDocument
     * @param string|null $direction
     * @param string|null $organ
     * @param string $theme
     */
    public function updateLinks(
        string $typeDocument = null,
        string $direction = null,
        string $organ = null,
        string $theme = null
    ): void
    {
        $list = $this->document->find('div.dark-box ul.doc-list li');

        foreach ($list as $item) {
            $href = pq($item)->find('a')->attr('href');
            $spider = Spider::updateModel($href, $typeDocument, $direction, $organ, $theme);
            if (!$spider) {
                Console::stdout("The $href href is not exist" . PHP_EOL);
                continue;
            }
            $spider->save();
        }
    }

    /**
     * @return bool
     */
    public function isEnd(): bool
    {
        if (!$this->isPagination()) {
            return true;
        }
        $current = $this->document->find('ul.pages li.current');
        $next = $current->next();
        return $next->hasClass('counter');
    }


    /**
     * @return bool
     */
    public function isPagination(): bool
    {
        $pages = $this->document->find('ul.pages');
        return !!$pages->length;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        $p = $this->document->find('div.story.issue p');
        if ($p->text() == 'Документы, удовлетворяющие данным условиям поиска, не найдены.') {
            return true;
        }
        return false;
    }
}