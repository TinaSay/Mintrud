<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.06.2017
 * Time: 13:22
 */

declare(strict_types = 1);


namespace app\modules\event\components;

use DateTime;
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
     * @return int|null
     */
    public function getId(): ?int
    {
        $path = explode('/', $this->getPath());
        $id = (int)array_pop($path);
        if ($id === 0) {
            return null;
        } else {
            return $id;
        }
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
    public function getText(): ?string
    {
        return $this->getData($this->getColumn(static::$columns['vars']), ['mercury_content', 'description', 'value']);
    }

    /**
     * @return string|null
     */
    public function getDate(): ?string
    {
        $date = $this->getColumn(static::$columns['timestamp']);
        if ('0.0.0000 00:00:00' != $date) {
            return DateTime::createFromFormat('d.m.Y H:i:s', $date)->format('Y-m-d H:i:s');
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getLanguage(): ?string
    {
        $lang = $this->getColumn(static::$columns['lang']);
        if ($lang == 'ru') {
            return 'ru-RU';
        } else {
            return null;
        }
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
        if (is_null($this->getId())) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isPage(): bool
    {
        if (!$this->isCategory() &&
            !is_null($this->getText()) &&
            !is_null($this->getDate()) &&
            !is_null($this->getLanguage())
        ) {
            return true;
        } else {
            return false;
        }
    }
}