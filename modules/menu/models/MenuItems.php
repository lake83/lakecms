<?php

namespace app\modules\menu\models;

use Yii;
use app\modules\translations\components\i18nActiveRecord;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

/**
 * This is the model class for table "menu_items".
 *
 * @property integer $id
 * @property string $title
 * @property integer $menu_id
 * @property integer $parent_id
 * @property integer $page_id
 * @property string $link
 * @property string $options
 * @property string $before_link
 * @property string $after_link
 * @property integer $only_guest_show
 * @property integer $guest_not_show
 * @property integer $is_active
 *
 * @property Menu $menu
 */
class MenuItems extends i18nActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'menu_id'], 'required'],
            [['menu_id', 'parent_id', 'page_id', 'only_guest_show', 'guest_not_show', 'is_active'], 'integer'],
            [['title', 'link', 'options', 'before_link', 'after_link'], 'string', 'max' => 255],
            [['parent_id', 'page_id'], 'default', 'value' => 0],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'id']],
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
            'menu_id' => 'Меню',
            'parent_id' => 'Родитель',
            'page_id' => 'Ссылается на страницу',
            'link' => 'Ссылка',
            'options' => 'Атрибуты ссылки',
            'before_link' => 'Текст перед ссылкой',
            'after_link' => 'Текст после ссылки',
            'only_guest_show' => 'Показовать только гостям',
            'guest_not_show' => 'Показовать только авторизованым',
            'is_active' => 'Активно',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        TagDependency::invalidate(Yii::$app->cache, 'menu');
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        TagDependency::invalidate(Yii::$app->cache, 'menu');
        
        parent::afterDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }
    
    /**
     * Список пунктов меню
     * @param integer $menu_id ID меню
     * @return array 
     */
    public static function getAll($menu_id = null)
    {
        return ArrayHelper::map(self::find()->where(['is_active' => 1] + (!is_null($menu_id) ? ['menu_id' => $menu_id] : []))->all(),'id','title');
    }
}
