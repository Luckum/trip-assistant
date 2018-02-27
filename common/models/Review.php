<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $author
 * @property string $message
 * @property string $created_at
 * @property string $updated_at
 * @property integer $visibility
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author', 'message'], 'required'],
            [['message'], 'string'],
            [['created_at', 'published_at'], 'safe'],
            [['visibility'], 'integer'],
            [['author'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author' => 'Автор',
            'message' => 'Сообщение',
            'created_at' => 'Создан',
            'published_at' => 'Дата публикации',
            'visibility' => 'Опубликован',
        ];
    }
    
    public static function getLastReviews()
    {
        return self::find()->where(['visibility' => 1])->orderBy('published_at DESC')->limit(5)->all();
    }
    
    public static function getPublished()
    {
        return self::find()->where(['visibility' => 1])->orderBy('published_at DESC')->all();
    }
    
    public static function getNotPublishedCnt()
    {
        return self::find()->where(['visibility' => 0])->count();
    }
}
