<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 14:59
 */

namespace app\modules\favorite\forms\frontend;

use app\modules\favorite\source\SourceInterface;
use app\modules\favorite\storage\StorageInterface;
use DateTime;
use Yii;
use yii\base\Model;

/**
 * Class AddForm
 *
 * @package app\modules\favorite\forms\frontend
 */
class AddForm extends Model
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $language;

    /**
     * @var int
     */
    private $createdBy;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $updatedAt;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * AddForm constructor.
     *
     * @param StorageInterface $storage
     * @param array $config
     */
    public function __construct(StorageInterface $storage, array $config = [])
    {
        $this->storage = $storage;
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'string', 'max' => 256],
            [['createdBy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['language'], 'string', 'max' => 8],
            [['url'], 'required'],
            ['url', 'uniqueUrl'],
        ];
    }

    /**
     * @return bool
     */
    public function uniqueUrl()
    {
        /** @var SourceInterface $source */
        $source = Yii::createObject([
            'class' => SourceInterface::class,
            'title' => $this->getTitle(),
            'url' => $this->getUrl(),
        ], [null]);

        $result = $this->storage->exist($source);

        return $result;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'title',
            'url',
            'language',
            'createdBy',
            'createdAt',
            'updatedAt',
        ];
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        $this->language = Yii::$app->language;

        return $this->language;
    }

    /**
     * @return int
     */
    public function getCreatedBy(): ?int
    {
        $this->createdBy = Yii::$app->getUser()->getId();

        return $this->createdBy;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        $this->createdAt = (new DateTime())->format('Y-m-d H:i:s');

        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        $this->updatedAt = (new DateTime())->format('Y-m-d H:i:s');

        return $this->updatedAt;
    }
}
