<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 14.07.17
 * Time: 16:37
 */

namespace app\modules\reception\services;

use app\modules\cabinet\models\Client;
use app\modules\config\helpers\Config;
use app\modules\reception\components\AppealDto;
use app\modules\reception\components\SoapClientComponent;
use app\modules\reception\form\AppealForm;
use app\modules\reception\models\Appeal;
use app\modules\reception\models\AppealFiles;
use Yii;
use yii\db\Expression;
use yii\log\Logger;
use yii\web\UploadedFile;

/**
 * Class SendAppeal
 *
 * @package app\modules\reception\services
 */
class SendAppealService
{
    const DEBUG_NONE = 0;
    const DEBUG_LOG = 1;
    const DEBUG_FILES = 2;

    /**
     * @var SoapClientComponent
     */
    private $client;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @return SoapClientComponent
     */
    private function getClient()
    {
        if (!$this->client) {
            $this->client = Yii::$app->get('sedo');
        }

        return $this->client;
    }

    /**
     * @param AppealForm $form
     * @param Appeal $model
     *
     * @return bool
     */
    public function send(AppealForm $form, Appeal $model)
    {
        $this->debug = Config::getValue('appeal_debug');
        $this->log(get_class($form) . PHP_EOL .
            print_r($form->getAttributes(), true));
        $appealXml = $this->saveAppeal(new AppealDto($form));
        try {
            $model->setAttributes([
                'theme' => $form->getTheme(),
                'status' => Appeal::STATUS_NONE,
                'type' => $form->getReplyCode(),
                'email' => $form->getEmail(),
            ]);
            if (!$model->save()) {
                $this->log(
                    'Fail to save Appeal model' . PHP_EOL .
                    print_r($model->getErrors(), true) . PHP_EOL);

                return false;
            };

            // try to link appeal with user when it is guest
            if (Yii::$app->user->getIsGuest() && $form->getEmail()) {
                $client = Client::findOne(['email' => $form->getEmail()]);
                if ($client) {
                    $model->client_id = $client->id;
                    $model->detachBehavior('CreatedByBehavior');
                }
            }

            if ($form->getAttachments()) {
                $savedAttachments = [];
                foreach ($form->getAttachments() as $file) {
                    if ($file instanceof UploadedFile) {
                        $fileModel = new AppealFiles([
                            'appeal_id' => $model->id,
                            'name' => $file->name,
                            'size' => $file->size,
                            'src' => $file,
                        ]);
                        if ($fileModel->save()) {
                            array_push($savedAttachments, $fileModel->getUploadPath('src'));
                        }
                        $model->link('files', $fileModel);
                    } else {
                        array_push($savedAttachments, $file);
                    }
                }
                $form->setAttachments($savedAttachments);
            }

            $form->setUid($model->id);

            $result = $this->getClient()->sendAppeal($form);
            if (!$result) {
                $this->log($this->getClient()->getLastResponse() . PHP_EOL);
            }
            $this->log(get_class($model) . PHP_EOL .
                print_r($model->getAttributes(), true));

            // remove appeal real files
            if ($this->debug !== self::DEBUG_FILES) {
                /** @var AppealFiles $files */
                $files = AppealFiles::find()->where([
                    'appeal_id' => $model->id,
                ])->all();
                if ($files) {
                    foreach ($files as $fileModel) {
                        if (file_exists($fileModel->getUploadPath('src'))) {
                            @unlink($fileModel->getUploadPath('src'));
                        }
                    }
                    AppealFiles::updateAll(['src' => ''], ['appeal_id' => $model->id]);
                }
            }
            if ($result) {
                $this->log('OK');
                $model->setAttributes([
                    'status' => $result['State'],
                    'comment' => $result['Comment'],
                    'ok' => ($result['BinaryDataLength'] == $result['XmlDataLength'] ? Appeal::MESSAGE_OK : Appeal::MESSAGE_FAIL),
                ]);
                $model->save();

                if ($appealXml) {
                    @unlink($appealXml);
                }

                return true;
            }
            $this->log('Fail!');
        } catch (\Exception $e) {
            Yii::error($e->getMessage() . PHP_EOL .
                $e->getTraceAsString(), 'sedo-error');
        }

        return false;
    }

    /**
     * @param Appeal $model
     *
     * @return bool
     */
    public function getStatus(Appeal $model)
    {
        try {
            $result = $this->getClient()->getAppealStatus($model);

            if ($result) {
                $model->setAttributes([
                    'status' => $result['State'],
                    'comment' => $result['Comment'],
                    'reg_number' => $result['RegNumber'],
                    'updated_at' => new Expression('NOW()'),
                ]);
                $model->save();

                return true;
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage() . PHP_EOL .
                $e->getTraceAsString(), 'sedo-error');
        }

        return false;
    }

    /**
     * @param Appeal $model
     *
     * @return bool
     */
    public function setStatus(Appeal $model)
    {
        try {
            $result = $this->getClient()->setAppealStatus($model);

            if ($result) {
                $model->setAttributes([
                    'status' => $result['State'],
                    'comment' => $result['Comment'],
                    'updated_at' => new Expression('NOW()'),
                ]);
                $model->save();

                return true;
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage() . PHP_EOL .
                $e->getTraceAsString(), 'sedo-error');
        }

        return false;
    }

    /**
     * @param $message
     * @param int $level
     */
    public function log($message, $level = Logger::LEVEL_INFO)
    {
        if ($this->debug == null) {
            $this->debug = Config::getValue('appeal_debug');
        }
        if ($this->debug > self::DEBUG_LOG) {
            Yii::getLogger()->log($message, $level, 'sedo');
        }
    }

    /**
     * @param AppealDto $dto
     *
     * @return bool|string
     */
    public function saveAppeal(AppealDto $dto)
    {
        if ($this->debug == null) {
            $this->debug = Config::getValue('appeal_debug');
        }
        if ($this->debug == self::DEBUG_FILES) {
            $filename = Yii::getAlias('@runtime/logs/appeal_' . time() . '.xml');
            $fh = fopen($filename, 'wb');
            fwrite($fh, (string)$dto);
            fclose($fh);

            return $filename;
        }

        return false;
    }

    /**
     * @param int $time
     *
     * @return bool|null|string
     */
    public static function getAppeal(int $time)
    {
        $filename = Yii::getAlias('@runtime/logs/appeal_' . $time . '.xml');
        if (file_exists($filename)) {
            return $filename;
        }

        return null;
    }
}