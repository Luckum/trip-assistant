<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service_content_price".
 *
 * @property integer $id
 * @property integer $service_id
 * @property string $formula
 *
 * @property Service $service
 */
class ServiceContentPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_content_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'formula'], 'required'],
            [['service_id'], 'integer'],
            [['formula'], 'string'],
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
            'formula' => 'Formula',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }
}
