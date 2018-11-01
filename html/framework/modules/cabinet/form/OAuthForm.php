<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 06.07.17
 * Time: 11:55
 */

namespace app\modules\cabinet\form;

use app\modules\cabinet\models\Client;
use DateTime;
use yii\base\Model;

/**
 * Class OAuthForm
 *
 * @package app\modules\cabinet\form
 */
class OAuthForm extends Model
{
    /**
     * @var integer
     */
    private $client_id = null;

    /**
     * @var string
     */
    private $source = null;

    /**
     * @var string
     */
    private $source_id = null;

    /**
     * @var string
     */
    private $created_at = null;

    /**
     * @var string
     */
    private $updated_at = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['client_id', 'source', 'source_id'], 'required'],
            [['client_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['source', 'source_id'], 'string', 'max' => 256],
            [
                ['client_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Client::className(),
                'targetAttribute' => ['client_id' => 'id'],
            ],
        ];
    }

    /**
     * @return int
     */
    public function getClientId(): int
    {
        return $this->client_id;
    }

    /**
     * @param int $client_id
     */
    public function setClientId(int $client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getSourceId(): string
    {
        return $this->source_id;
    }

    /**
     * @param string $source_id
     */
    public function setSourceId(string $source_id)
    {
        $this->source_id = $source_id;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        $this->created_at = new DateTime();

        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        $this->updated_at = new DateTime();

        return $this->updated_at;
    }
}
