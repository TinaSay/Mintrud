<?php

namespace app\modules\atlas\models;


use app\modules\atlas\interfaces\TypeInterface;
use yii\helpers\ArrayHelper;

class AtlasDirectoryYear extends AtlasDirectory implements TypeInterface
{

    /**
     * @return int
     */
    public static function getType(): int
    {
        return static::TYPE_YEAR;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Год (справочник)';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['code'], 'integer'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'title' => 'Год (прописью)',
            'code' => 'Год (число)',
        ]);
    }

    /**
     * @return array
     */
    public static function asDropDown()
    {
        return static::find()
            ->select(['title', 'code'])
            ->where([
                'type' => static::getType(),
            ])
            ->indexBy('code')
            ->column();
    }
}
