<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service_content_field".
 *
 * @property integer $id
 * @property integer $service_id
 * @property string $field_type
 * @property string $field_id
 * @property string $field_label
 *
 * @property Service $service
 * @property ServiceContentOption[] $serviceContentOptions
 */
class ServiceContentField extends \yii\db\ActiveRecord
{
    const TYPE_TEXT = 'text';
    const TYPE_DATE = 'date';
    const TYPE_SELECT = 'select';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_content_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'field_type', 'field_id', 'field_label'], 'required'],
            [['service_id'], 'integer'],
            [['field_type'], 'string'],
            [['field_id'], 'string', 'max' => 20],
            [['field_label'], 'string', 'max' => 100],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Service::className(), 'targetAttribute' => ['service_id' => 'id']],
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
            'field_type' => 'Filed Type',
            'field_id' => 'Field ID',
            'field_label' => 'Field Label',
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
    public function getServiceContentOptions()
    {
        return $this->hasMany(ServiceContentOption::className(), ['field_id' => 'id']);
    }
}
