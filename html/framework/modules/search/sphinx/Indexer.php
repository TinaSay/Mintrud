<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 28.08.17
 * Time: 17:19
 */

namespace app\modules\search\sphinx;

use krok\search\interfaces\ConfigureInterface;
use krok\search\interfaces\ConnectorInterface;
use krok\search\interfaces\ItemInterface;
use Yii;
use yii\base\ErrorException;

/**
 * Class Indexer
 *
 * @package app\modules\search\sphinx
 */
class Indexer extends \krok\search\sphinx\Indexer
{
    /**
     * Indexer constructor.
     *
     * @param ConfigureInterface $configure
     * @param ConnectorInterface $connector
     * @param string $db
     */
    public function __construct(ConfigureInterface $configure, ConnectorInterface $connector, string $db = 'db')
    {
        parent::__construct($configure, $connector, $db);
        ini_set('memory_limit', -1);
    }

    /**
     * @param ItemInterface $item
     * @param array $model
     *
     * @return array
     */
    protected function fields(ItemInterface $item, array $model)
    {
        $rows = [];

        try {

            $rows[] = $this->iteration++;

            foreach ($item->getFields() as $closure) {
                $rows[] = str_replace("\0", '', call_user_func($closure, $model));
            }

        } catch (ErrorException $e) {
            echo $e->getMessage() . PHP_EOL . $e->getCode();
            Yii::error($e->getMessage() . PHP_EOL . $e->getCode() . PHP_EOL . $e->getSeverity() . PHP_EOL . $e->getFile() . PHP_EOL . $e->getLine() . PHP_EOL . $e->getPrevious(),
                'search');
        }

        return $rows;
    }
}
