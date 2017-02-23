<?php

namespace app\modules\contentblock\models;

use Yii;
use yii\caching\TagDependency;

/**
 * This is the model class for table "blocks".
 *
 * @property integer $id
 * @property integer $page_id
 * @property integer $type
 * @property integer $contentblock_id
 * @property string $widget_action
 * @property string $layout
 * @property string $position
 *
 * @property Contentblock $contentblock
 */
class Blocks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blocks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'position'], 'required'],
            [['contentblock_id', 'layout'], 'required', 'on' => 'contentblock'],
            [['widget_action'], 'required', 'on' => 'widget'],
            [['page_id', 'type', 'contentblock_id'], 'integer'],
            [['widget_action'], 'string', 'max' => 100],
            [['layout', 'position'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_id' => 'Страница',
            'type' => 'Тип',
            'contentblock_id' => 'Контентный блок',
            'widget_action' => 'Виджет',
            'layout' => 'Шаблон',
            'position' => 'Позиция',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        TagDependency::invalidate(Yii::$app->cache, 'pages');
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function afterDelete()
    {
        TagDependency::invalidate(Yii::$app->cache, 'pages');
         
        parent::afterDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentblock()
    {
        return $this->hasOne(Contentblock::className(), ['id' => 'contentblock_id'])->andOnCondition(['is_active' => 1])->localized();
    }
}