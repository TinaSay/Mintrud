<?php


namespace app\modules\media\models;

/**
 * This is the model class for table "{{%storage}}".
 *
 * @property integer $id
 * @property string $model
 * @property integer $recordId
 * @property string $attribute
 * @property string $title
 * @property string $hint
 * @property string $src
 * @property string $url
 * @property string $mime
 * @property integer $size
 * @property integer $createdBy
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $sortOrder
 */
class Storage extends \krok\storage\models\Storage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'recordId', 'attribute', 'src', 'mime', 'size'], 'required'],
            [['recordId', 'size', 'createdBy', 'sortOrder'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['model', 'title', 'mime', 'url'], 'string', 'max' => 128],
            [['hint'], 'string'],
            [['attribute', 'src'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Модель',
            'recordId' => 'Запись',
            'attribute' => 'Атрибут',
            'title' => 'Заголовок',
            'hint' => 'Подсказака',
            'src' => 'Файл',
            'url' => 'URL',
            'mime' => 'MIME',
            'size' => 'Размер',
            'createdBy' => 'Создана',
            'createdAt' => 'Создано',
            'updatedAt' => 'Обновлено',
            'sortOrder' => 'Порядок сортировки',
        ];
    }
}