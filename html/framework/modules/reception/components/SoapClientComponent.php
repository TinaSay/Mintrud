<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 14.07.17
 * Time: 11:27
 */

namespace app\modules\reception\components;


use app\modules\reception\form\AppealForm;
use app\modules\reception\models\Appeal;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class SoapClientComponent
 *
 * @package app\modules\reception\components
 */
class SoapClientComponent extends Component
{
    /**
     * @var string|null
     */
    public $login;

    /**
     * @var string|null
     */
    public $password;

    /**
     * @var string
     */
    public $url;

    /**
     * @var \SoapClient
     *
     * @method UploadDocument($data)
     * @method CheckDocument($data)
     */
    protected $client;

    /**
     * SoapClientComponent constructor.
     *
     * @param array $config
     *
     * @throws InvalidConfigException
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['url'])) {
            throw new InvalidConfigException('Property "url" must be set');
        }
        parent::__construct($config);
    }

    /**
     * @return void
     */
    protected function initClient()
    {
        if (!$this->client) {
            $options = [
                'trace' => 1,
                'soap_version' => SOAP_1_2,
                'exceptions' => true,
                'connection_timeout' => 10,
            ];
            if ($this->login) {
                $options['login'] = $this->login;
                $options['password'] = $this->password;
            }
            $this->client = new \SoapClient($this->url, $options);
        }
    }

    /**
     * @param $method
     * @param array $params
     *
     * @return mixed
     */
    protected function call($method, array $params)
    {
        if (!$this->url) {
            return false;
        }
        $this->initClient();

        return $this->client->__soapCall($method, [$params]);
    }

    /**
     * @param AppealForm $form
     *
     * @return array|bool
     * @throws \SoapFault
     */
    public function sendAppeal(AppealForm $form)
    {
        $xml = (string)(new AppealDto($form));

        $result = $this->call('SendDocument', [
            'portalMessageData' => [
                'DocumentId' => $form->getUid(),
                'BinaryData' => $xml,
            ],
        ]);

        if ($result) {
            $documentResult = $result->SendDocumentResult;

            return [
                'State' => property_exists($documentResult, 'State') ? $documentResult->State : '',
                'Comment' => property_exists($documentResult, 'Comment') ? $documentResult->Comment : '',
                'BinaryDataLength' => property_exists($documentResult, 'BinaryDataLength') ?
                    $documentResult->BinaryDataLength : '',
                'XmlDataLength' => strlen($xml),
            ];
        }

        return false;
    }

    /**
     * @param Appeal $model
     *
     * @return array|bool
     */
    public function getAppealStatus(Appeal $model)
    {
        $result = $this->call('GetDocumentState', [
            'documentId' => $model->id,
        ]);

        if ($result) {
            $documentStateResult = $result->GetDocumentStateResult;

            return [
                'State' => property_exists($documentStateResult, 'State') ? $documentStateResult->State : '',
                'Comment' => property_exists($documentStateResult, 'Comment') ? $documentStateResult->Comment : '',
                'RegNumber' => property_exists($documentStateResult,
                    'RegNumber') ? $documentStateResult->RegNumber : '',
                'RegDate' => property_exists($documentStateResult, 'RegDate') ? $documentStateResult->RegDate : '',
            ];
        }

        return false;
    }

    /**
     * @param Appeal $model
     *
     * @return array|bool
     */
    public function setAppealStatus(Appeal $model)
    {
        $result = $this->call('SetDocumentState', [
            'documentId' => $model->id,
            'state' => $model->status,
        ]);

        if ($result) {
            $documentStateResult = $result->SetDocumentStateResult;

            return [
                'State' => property_exists($documentStateResult, 'State') ? $documentStateResult->State : '',
                'Comment' => property_exists($documentStateResult, 'Comment') ? $documentStateResult->Comment : '',
            ];
        }

        return false;
    }

    /**
     * @return string
     */
    public function getLastRequest()
    {
        return $this->client ? $this->client->__getLastRequest() : '';
    }

    /**
     * @return string
     */
    public function getLastResponse()
    {
        return $this->client ? $this->client->__getLastResponse() : '';
    }
}