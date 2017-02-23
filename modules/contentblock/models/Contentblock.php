<?php
namespace app\modules\contentblock\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use app\modules\translations\components\i18nActiveRecord;
use yii\caching\TagDependency;

class Contentblock extends i18nActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contentblock}}';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            ['is_active', 'integer'],
            [['js', 'css'], 'safe']
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
            'text' => 'Содержание',
            'js' => 'Javascript',
            'css' => 'CSS',
            'is_active' => 'Активно',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен'
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
    
    public static function getAll()
    {
        return ArrayHelper::map(self::find()->where(['is_active' => 1])->all(),'id','title');
    }        
}