<?php

namespace app\modules\user\models;

class Permissions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%permissions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['actions'], 'string'],
            [['user_status', 'module'], 'string', 'max' => 50]
        ];
    }
}