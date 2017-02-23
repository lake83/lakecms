<?php

namespace app\modules\news\models;

use Yii;
use app\modules\translations\components\i18nActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $text
 * @property string $image
 * @property string $seo_key
 * @property string $seo_description
 * @property integer $is_active
 * @property integer $created_at
 * @property integer $updated_at
 */
class News extends i18nActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['text', 'seo_description'], 'string'],
            [['is_active', 'created_at', 'updated_at'], 'integer'],
            [['title', 'slug', 'seo_key'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'slug' => 'Алиас',
            'text' => 'Текст',
            'image' => 'Изображение',
            'seo_key' => 'Ключевые слова (SEO)',
            'seo_description' => 'Описание (SEO)',
            'is_active' => 'Активно',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен'
        ];
    }
}