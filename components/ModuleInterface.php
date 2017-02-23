<?php
namespace app\components;

interface ModuleInterface
{
    /**
     * Меню модуля
     * modules_show - выводить в общем списке меню модулей boolen
     * url должен быть вида [/module_id/controller_id/action_id] или '#' - не участвует в проверке на права доступа, использовать как родительский для подменю
     * @tutorial
     * return [
         'modules_show' => false,
         'items' => [['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['/login'], 'visible' => \Yii::$app->user->isGuest],
                    [
                        'label' => 'Same tools',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug'],]
                        ],
                    ],
                ]];
     * @return array
     */
    public static function getMenu();
    
    /**
     * Действия доступные для указания прав пользователя в модуле
     * @tutorial
     * return [
           'user' => [                          //ID контроллера
               'controller' => 'Пользователи',  //Название контроллера
               'index' => 'Список',
               'create' => 'Добавить',
               'update' => 'Обновить',
               'delete' => 'Удалить'
           ]
       ];
     * @return array
     */
    public static function getPermissions();
    
    /**
     * Модели и их атрибуты для хранения их переводов в БД, если переводы в моделях модуля не нужны указать return false;
     * id - название колонки в таблице БД соответствующей модели, label - название поля, 
     * field - тип поля (возможные варианты: text - текстовое поле, textarea - текстовая область, redactor - WYSIWYG редактор)
     * @tutorial
     * return [
           'Contentblock' => [                  //Название модели
               'title' => 'Контекстные блоки',  //Название модели для вывода пользователю
               'controller' => 'contentblock',  //ID контроллера где находится update основной модели
               'attributes' => [
                   ['id' => 'title', 'label' => 'Название', 'field' => 'text'],
                   ['id' => 'text', 'label' => 'Содержание', 'field' => 'redactor']
               ]
           ]
       ];
     * @return array | boolean
     */
    public static function getTranslations();
}