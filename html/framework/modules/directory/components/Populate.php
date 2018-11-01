<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.06.2017
 * Time: 13:22
 */

declare(strict_types = 1);


namespace app\modules\directory\components;


use app\modules\directory\models\Directory;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Populate
{

    private $row;
    private $condition;
    private $lengthCondition;

    private static $columns = [
        'id' => 0,
        'path' => 1,
        'cat' => 2,
        'vars' => 3,
        'depend' => 4,
        'template' => 5,
        'lang' => 6,
        'activity' => 7,
        'pos' => 8,
        'meta_id' => 9,
        'timestamp' => 10,
    ];

    /**
     * Populate constructor.
     * @param $row array
     * @param $condition string
     * @param $lengthCondition integer
     */
    public function __construct(array $row, string $condition, int $lengthCondition)
    {
        $this->row = $row;
        $this->condition = $condition;
        $this->lengthCondition = $lengthCondition;
    }

    /**
     * @return string
     */
    public function getIdPages(): string
    {
        return $this->getColumn(static::$columns['id']);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData($this->getColumn(static::$columns['depend']), ['page_title']);
    }

    /**
     * @return string
     */
    public function getFragment(): string
    {
        $path = explode('/', $this->getPath());
        $fragment = array_pop($path);
        return $fragment;
    }


    /**
     * @return int|null
     */
    public function getParentId(): ?int
    {
        $cats = $this->getDirectory();
        $url = $this->getParentUrl();
        if (array_key_exists($url, $cats)) {
            return (int)$cats[$url];
        }
        return null;
    }

    /**
     * @return string
     */
    public function getParentUrl(): string
    {
        $path = explode('/', $this->getPath());
        return implode('/', array_slice($path, 0, count($path) - 1));
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getPath();
    }


    /**
     * @return array
     */
    public function getDirectory(): array
    {
        return Directory::find()->select(['id', 'url'])->indexBy('url')->asArray()->column();
    }

    /**
     * @return string
     */
    private function getPath(): string
    {
        return ltrim($this->getColumn(static::$columns['path']), '/');
    }

    /**
     * @param int $column
     * @return string
     */
    private function getColumn(int $column): string
    {
        return ArrayHelper::getValue($this->row, $column);
    }


    /**
     * @param string $string
     * @param array $columns
     * @return null|string
     */
    private function getData(string $string, array $columns):? string
    {
        $json = base64_decode($string);
        json_decode($json, true);
        if (json_last_error() == JSON_ERROR_NONE) {
            return ArrayHelper::getValue(Json::decode($json), $columns);
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function isFitsCategory(): bool
    {
        $category = $this->getColumn(static::$columns['template']);
        if (strncasecmp($category, $this->condition, $this->lengthCondition) === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isCategory(): bool
    {
        $path = explode('/', $this->getPath());
        $id = (int)array_pop($path);
        if ($id === 0) {
            return true;
        } else {
            return false;
        }
    }
}