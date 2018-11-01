<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 03.07.16
 * Time: 20:13
 */

namespace app\commands;

use Yii;

/**
 * Class MigrateController
 *
 * @package app\commands
 */
class MigrateController extends \yii\console\controllers\MigrateController
{
    /**
     * @var array
     */
    public $paths = [];

    /**
     * @return int
     */
    public function actionUpAll()
    {
        $migrations = [];

        foreach ($this->paths as $path) {
            $this->migrationPath = Yii::getAlias($path);
            $newMigrations = $this->getNewMigrations();
            if (($count = count($newMigrations)) > 0) {
                $migrations = array_merge($migrations,
                    array_combine($newMigrations, array_fill(0, $count, $this->migrationPath)));
            }
        }

        ksort($migrations);

        foreach ($migrations as $migration => $migrationPath) {
            $this->migrationPath = $migrationPath;
            if (!$this->migrateUp($migration)) {
                return self::EXIT_CODE_ERROR;
            }
        }

        return self::EXIT_CODE_NORMAL;
    }
}
