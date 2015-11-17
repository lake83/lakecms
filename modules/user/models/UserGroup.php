<?php
namespace app\modules\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class UserGroup extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'status'], 'required'],
            [['status', 'is_active'], 'integer'],
            ['status', 'unique']
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
            'status' => 'Статус',
            'is_active' => 'Активно'
        ];
    }
    
    public static function getAll()
    {
        return ArrayHelper::map(self::find()->where(['is_active' => 1])->all(),'status','title');
    }
}