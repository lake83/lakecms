<?php
namespace app\modules\structure\models;

use Yii;
use app\modules\translations\components\i18nActiveRecord;
use app\modules\contentblock\models\Blocks;
use app\modules\menu\models\MenuItems;
use yii\caching\TagDependency;

class Pages extends i18nActiveRecord
{
    public static $pages_list;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'layout'], 'required'],
            [['parent_id', 'is_active'], 'integer'],
            [['title', 'seo_key'], 'string', 'max' => 255],
            ['url', 'unique', 'targetAttribute' => ['parent_id', 'url']],
            [['seo_description'], 'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'seo_key' => 'Ключевые слова',
            'seo_description' => 'Описание',
            'parent_id' => 'Родитель',
            'url' => 'Название страницы',
            'layout' => 'Шаблон',
            'is_active' => 'Активно'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert && array_key_exists('layout', $changedAttributes)) { //если изменен шаблон, удаляются установленные в нем блоки
            Blocks::deleteAll('page_id = :id', [':id' => $this->id]);
        }
        TagDependency::invalidate(Yii::$app->cache, 'pages');
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        if ($menu_item = MenuItems::find()->where(['page_id' => $this->id])->one()) {
            $menu_item->delete();
        }
        TagDependency::invalidate(Yii::$app->cache, 'pages'); 
        
        parent::afterDelete();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlocks()
    {
        return $this->hasMany(Blocks::className(), ['page_id' => 'id'])->with('contentblock');
    }
    
    /**
     * Список страниц с иерархической структурой
     * @return array 
     */
    public static function treeSelect()
    {
        $data = self::find()->select('id,title,parent_id,is_active')->where(['is_active' => 1])->asArray()->all();
        foreach ($data as $value){
            if($value['parent_id'] == 0){
                self::$pages_list[$value['id']] = $value['title'];
                self::dropDownTree($data, $value['id']);
            }
        }
        return self::$pages_list;
    }

    /**
     * Построение элементов иерархической структуры
     * @param array $array данные страниц
     * @param integer $parent_id ID родительской страницы
     * @param integer $level уровень страницы в структуре сайта
     */
    protected static function dropDownTree($array, $parent_id, $level = 1)
    {
        foreach ($array as $item){
            if($item['parent_id'] == $parent_id){
                self::$pages_list[$item['id']] = str_repeat('---', $level) . ' ' . $item['title'];
                self::dropDownTree($array, $item['id'], $level+1);
            }
        }
    }
}