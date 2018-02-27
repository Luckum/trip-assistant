<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service_content_option".
 *
 * @property integer $id
 * @property integer $service_id
 * @property integer $field_id
 * @property string $option_value
 * @property string $option_id
 * @property string $option_label
 *
 * @property Service $service
 * @property ServiceContentField $field
 */
class ServiceContentOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_content_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'field_id', 'option_value', 'option_id', 'option_label'], 'required'],
            [['service_id', 'field_id'], 'integer'],
            [['option_value', 'option_label'], 'string', 'max' => 50],
            [['option_id'], 'string', 'max' => 25],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Service::className(), 'targetAttribute' => ['service_id' => 'id']],
            [['field_id'], 'exist', 'skipOnError' => true, 'targetClass' => ServiceContentField::className(), 'targetAttribute' => ['field_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_id' => 'Service ID',
            'field_id' => 'Field ID',
            'option_value' => 'Option Value',
            'option_id' => 'Option ID',
            'option_label' => 'Option Label',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(ServiceContentField::className(), ['id' => 'field_id']);
    }
}
