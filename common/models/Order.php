<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $service_id
 * @property string $total
 * @property string $user_email
 * @property string $user_name
 * @property string $user_phone
 * @property string $created_at
 *
 * @property Service $service
 * @property OrderStatus[] $orderStatuses
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'total', 'user_email', 'user_name', 'user_phone'], 'required'],
            [['service_id'], 'integer'],
            [['total'], 'number'],
            [['created_at'], 'safe'],
            [['user_email', 'user_name'], 'string', 'max' => 100],
            [['user_phone'], 'string', 'max' => 20],
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
            'total' => 'Total',
            'user_email' => 'User Email',
            'user_name' => 'User Name',
            'user_phone' => 'User Phone',
            'created_at' => 'Created At',
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
    public function getOrderStatuses()
    {
        return $this->hasMany(OrderStatus::className(), ['order_id' => 'id']);
    }
}
