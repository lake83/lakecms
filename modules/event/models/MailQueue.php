<?php

namespace app\modules\event\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mail_queue".
 *
 * @property integer $id
 * @property string $subject
 * @property string $text
 * @property string $to
 * @property integer $created_at
 */
class MailQueue extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_queue';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
                'value' => time()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'text', 'to'], 'required'],
            [['text'], 'string'],
            [['created_at'], 'integer'],
            [['subject', 'to'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => 'Тема',
            'text' => 'Текст',
            'to' => 'От',
            'created_at' => 'Создан',
        ];
    }
}