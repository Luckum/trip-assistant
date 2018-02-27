<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property integer $visibility
 * @property string $slug
 * @property string $title
 * @property string $content
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visibility'], 'integer'],
            [['slug', 'title', 'content'], 'required'],
            [['content'], 'string'],
            [['slug'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visibility' => 'Видимость',
            'slug' => 'Заголовок для URL',
            'title' => 'Заголовок',
            'content' => 'Содержимое',
        ];
    }
    
    public static function getContentBySlug($slug)
    {
        $page = self::find()->where(['slug' => $slug, 'visibility' => 1])->one();
        return $page ? $page->content : "";
    }
    
    public static function getBySlug($slug)
    {
        return self::find()->where(['slug' => $slug, 'visibility' => 1])->one();
    }
}
