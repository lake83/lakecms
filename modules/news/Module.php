<?php

namespace app\modules\news;

class Module extends \yii\base\Module implements \app\components\ModuleInterface
{
    public $title = 'Новости';
    
    public static function getMenu()
    {
        return [
            'modules_show' => true,
            'items' => [
                ['label' => 'Новости', 'icon' => 'fa fa-newspaper-o', 'url' => ['/news/news/index']]
            ]
        ];
    }
    
    public static function getPermissions()
    {
        return [
            'news' => [
                'controller' => 'Новости',
                'index' => 'Список',
                'create' => 'Добавить',
                'update' => 'Обновить',
                'delete' => 'Удалить',
                'toggle' => 'Активировать'
            ]
        ];
    }
    
    public static function getTranslations()
    {
        return [
            'News' => [
                'title' => 'Новости',
                'controller' => 'news',
                'attributes' => [
                    ['id' => 'title', 'label' => 'Название', 'field' => 'text'],
                    ['id' => 'text', 'label' => 'Содержание', 'field' => 'redactor'],
                    ['id' => 'seo_key', 'label' => 'Ключевые слова (SEO)', 'field' => 'text'],
                    ['id' => 'seo_description', 'label' => 'Описание (SEO)', 'field' => 'textarea']
                ]
            ]
        ];
    }
}