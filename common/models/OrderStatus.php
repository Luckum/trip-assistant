<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_status".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $response_code
 * @property string $response_text
 * @property string $error
 * @property string $order_num
 * @property string $billing_name
 *
 * @property Order $order
 */
class OrderStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'response_code', 'response_text'], 'required'],
            [['order_id', 'response_code'], 'integer'],
            [['error'], 'string'],
            [['response_text'], 'string', 'max' => 100],
            [['order_num'], 'string', 'max' => 15],
            [['billing_name'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'response_code' => 'Response Code',
            'response_text' => 'Response Text',
            'error' => 'Error',
            'order_num' => 'Order Num',
            'billing_name' => 'Billing Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
