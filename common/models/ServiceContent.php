<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service_content".
 *
 * @property integer $id
 * @property integer $service_id
 * @property string $content
 * @property string $content_json
 */
class ServiceContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'content', 'content_json'], 'required'],
            [['service_id'], 'integer'],
            [['content', 'content_json'], 'string'],
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
            'content' => 'Content',
        ];
    }
}
