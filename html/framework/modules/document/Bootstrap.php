<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 02.10.17
 * Time: 16:52
 */

namespace app\modules\document;

use app\modules\document\interfaces\DownloadServiceInterface;
use app\modules\magic\models\Magic;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;

/**
 * Class Bootstrap
 *
 * @package app\modules\document
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @var DownloadServiceInterface
     */
    protected $service;

    /**
     * Bootstrap constructor.
     *
     * @param DownloadServiceInterface $service
     */
    public function __construct(DownloadServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $this->registerArchiving();
    }

    protected function registerArchiving()
    {
        Event::on(Magic::class, ActiveRecord::EVENT_AFTER_INSERT,
            function (Event $event) {
                $sender = $event->sender;
                $this->service->create($sender->record_id, true);
            });
        Event::on(Magic::class, ActiveRecord::EVENT_AFTER_UPDATE,
            function (Event $event) {
                $sender = $event->sender;
                $this->service->create($sender->record_id, true);
            });
        Event::on(Magic::class, ActiveRecord::EVENT_AFTER_DELETE,
            function (Event $event) {
                $sender = $event->sender;
                $this->service->create($sender->record_id, true);
            });
    }
}
