<?php

namespace app\modules\translations\models;

use Yii;

/**
 * This is the model class for table "translate_models".
 *
 * @property integer $id
 * @property string $module
 * @property string $model
 * @property integer $owner_id
 * @property string $column
 * @property string $lang
 * @property string $value
 *
 */
class TranslateModels extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'translate_models';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module', 'model', 'owner_id', 'column', 'lang'], 'required'],
            [['module', 'model', 'column'], 'string', 'max' => 50],
            [['lang'], 'string', 'min' => 2, 'max' => 5],
            [['owner_id'], 'integer'],
            [['value'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module' => 'Модуль',
            'model' => 'Модель',
            'owner_id' => 'ID в модели',
            'column' => 'Атрибут',
            'lang' => 'Язык',
            'value' => 'Значение',
        ];
    }
}
