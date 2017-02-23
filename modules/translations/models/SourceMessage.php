<?php

namespace app\modules\translations\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\translations\models\query\SourceMessageQuery;

class SourceMessage extends ActiveRecord
{
    public static function tableName()
    {
        return 'source_message';
    }
    
    public static function find()
    {
        return new SourceMessageQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['message', 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category' => 'Категория',
            'message' => 'Текст',
            'status' => 'Статус'
        ];
    }

    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id'])->indexBy('language');
    }

    /**
     * @return array|SourceMessage[]
     */
    public static function getCategories()
    {
        return SourceMessage::find()->select('category')->distinct('category')->asArray()->all();
    }

    public function initMessages()
    {
        $messages = [];
        if (isset(Yii::$app->params['languages']) && is_array(Yii::$app->params['languages']) && count(Yii::$app->params['languages']) > 1) {
            foreach (Yii::$app->params['languages'] as $language => $title) {
                if (!isset($this->messages[$language])) {
                    $message = new Message;
                    $message->language = $language;
                    $messages[$language] = $message;
                } else {
                    $messages[$language] = $this->messages[$language];
                }
            }
        }
        $this->populateRelation('messages', $messages);
    }

    public function saveMessages()
    {
        /** @var Message $message */
        foreach ($this->messages as $message) {
            if ($message->language !== Yii::$app->sourceLanguage) {
                $this->link('messages', $message);
                if ($message->save()) {
                    foreach (array_keys(Yii::$app->params['languages']) as $language) {
                        Yii::$app->cache->delete(['yii\i18n\DbMessageSource', $this->category, $language]);
                    }
                }
            }
        }
    }

    public function isTranslated()
    {
        foreach ($this->messages as $message) {
            if (!$message->translation) {
                return false;
            }
        }
        return true;
    }
}
