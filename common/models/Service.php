<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $priority
 */
class Service extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'priority'], 'required'],
            [['priority'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'priority' => 'Приоритет',
        ];
    }
    
     
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceContent()
    {
        return $this->hasOne(ServiceContent::className(), ['service_id' => 'id']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceContentFields()
    {
        return $this->hasMany(ServiceContentField::className(), ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceContentOptions()
    {
        return $this->hasMany(ServiceContentOption::className(), ['service_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceContentPrice()
    {
        return $this->hasOne(ServiceContentPrice::className(), ['service_id' => 'id']);
    }
    
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $prev = self::find()->max('priority');
                if ($prev) {
                    $this->priority = $prev + 1;
                } else {
                    $this->priority = 1;
                }
                $this->slug = str_replace(' ', '-', strtolower(trim($this->name)));
                return true;
            }
            return true;
        }
        return false;
    }
    
    public static function getPriorityLess($priority)
    {
        return self::find()->where(['<', 'priority', $priority])->orderBy('priority DESC')->limit(1)->one();
    }
    
    public static function getPriorityGreater($priority)
    {
        return self::find()->where(['>', 'priority', $priority])->orderBy('priority ASC')->limit(1)->one();
    }
}
