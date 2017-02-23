<?php

namespace app\modules\structure;

class Module extends \yii\base\Module implements \app\components\ModuleInterface
{
    public $title = 'Структура';
    
    public static function getMenu()
    {
        return [
            'modules_show' => false,
            'items' => static::menuRecrusive(0)
        ];
    }
    
    private static function menuRecrusive($parent)
    {
        $result = [];
        if ($items = \app\modules\structure\models\Pages::find()->select('id,title,url,parent_id,is_active')->where(['parent_id' => $parent])->asArray()->all())
            foreach ($items as $key => $item) {
                $result[] = ['label' => (!empty($item['title']) ? $item['title'] : $item['url']).($item['is_active'] ? '' : ' <i style="color:#dd4b39">(не активен)</i>'),
                    'url' => ['/structure/structure/index', 'id' => $item['id']], 'items' => static::menuRecrusive($item['id'])];
                if ($item['parent_id'] == 0)
                    $result[$key]['icon'] = 'fa fa-home';
            }
        return $result;
    }
    
    public static function getPermissions()
    {
        return [
            'structure' => [
                'controller' => 'Структура',
                'index' => 'Список',
                'create' => 'Добавить',
                'update' => 'Обновить',
                'delete' => 'Удалить',
                'page' => 'Страницы',
                'text' => 'Контекстный блок',
                'widget' => 'Виджет',
                'clear' => 'Очистить блок'
            ]
        ];
    }
    
    public static function getTranslations()
    {
        return [
            'Pages' => [
                'title' => 'Страницы',
                'controller' => 'structure',
                'attributes' => [
                    ['id' => 'title', 'label' => 'Заголовок', 'field' => 'text'],
                    ['id' => 'seo_key', 'label' => 'Ключевые слова', 'field' => 'text'],
                    ['id' => 'seo_description', 'label' => 'Описание', 'field' => 'textarea']
                ]
            ]
        ];
    }
}