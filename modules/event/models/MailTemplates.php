<?php

namespace app\modules\event\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "mail_templates".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $subject
 * @property string $text
 * @property integer $is_active
 */
class MailTemplates extends \app\modules\translations\components\i18nActiveRecord
{
    /**
	 * Шаблон для переменных в письме
	 */
	const VAR_TEMPLATE = '/\[%s\]/i';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_templates';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
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
            [['title', 'slug', 'subject', 'text'], 'required'],
            [['text'], 'string'],
            [['is_active'], 'integer'],
            [['title', 'slug', 'subject'], 'string', 'max' => 255],
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
            'subject' => 'Тема',
            'is_active' => 'Активно',
        ];
    }
}