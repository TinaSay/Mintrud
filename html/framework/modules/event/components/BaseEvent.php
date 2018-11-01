<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.07.2017
 * Time: 9:50
 */

declare(strict_types = 1);


namespace app\modules\event\components;

use phpQuery;
use phpQueryObject;
use yii\httpclient\Response;

/**
 * Class BaseDocument
 * @package app\modules\document\components
 */
abstract class BaseEvent
{
    /** @var phpQueryObject */
    protected $document;

    /**
     * BaseDocument constructor.
     * @param phpQueryObject $document
     */
    public function __construct(phpQueryObject $document)
    {
        $this->document = $document;
    }

    /**
     * @param Response $response
     * @return phpQueryObject
     */
    public static function newDocument(Response $response): phpQueryObject
    {
        file_put_contents(\Yii::getAlias('@runtime') . '/html/response.html', $response->content); // TODO delete
        return phpQuery::newDocument(mb_convert_encoding($response->content, 'UTF-8', 'CP1251'));
    }
}