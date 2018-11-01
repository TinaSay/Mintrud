<?php

namespace app\modules\document\models;

use Yii;

/**
 * This is the model class for table "{{%document_document_direction}}".
 *
 * @property integer $id
 * @property integer $document_id
 * @property integer $document_direction_id
 *
 * @property Document $document
 * @property DocumentDirection $documentDirection
 */
class DocumentDirection extends \yii\db\ActiveRecord
{
    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%document_document_direction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_id', 'document_direction_id'], 'integer'],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['document_direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentDirection::className(), 'targetAttribute' => ['document_direction_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'document_id' => Yii::t('system', 'Document ID'),
            'document_direction_id' => Yii::t('system', 'Document Direction ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentDirection()
    {
        return $this->hasOne(DocumentDirection::className(), ['id' => 'document_direction_id']);
    }
}
