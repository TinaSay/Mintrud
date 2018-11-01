<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.06.2017
 * Time: 14:54
 */

declare(strict_types = 1);


namespace app\modules\directory\models\query;


use app\modules\directory\models\Directory;
use Yii;
use yii\db\ActiveQuery;

trait LanguageTrait
{
    /**
     * @param null|string $language
     *
     * @return $this|ActiveQuery
     */
    public function language($language = null): ActiveQuery
    {
        if ($language === null) {
            $language = Yii::$app->language;
        }
        return $this->andWhere([Directory::tableName() . '.[[language]]' => $language]);
    }
}