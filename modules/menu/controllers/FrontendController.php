<?php

namespace app\modules\menu\controllers;

use Yii;
use app\modules\menu\models\Menu;
use app\modules\structure\models\Pages;
use app\components\CmsHelper;

/**
 * FrontendController implements the frontend actions for Menu module.
 */
class FrontendController extends \app\controllers\BaseFrontendController
{
    public $menu = [
        'menu/frontend/main' => 'Главное меню'
    ];
    
    /**
     * @var array элементы меню
     */
    private $items_list;
    
    public function actionMain()
    {
       $cache_key = 'glavnoe-menu__' . (Yii::$app->user->isGuest ? 'guest__' : '') . Yii::$app->language;
       
       if (!$items = Yii::$app->cache->get($cache_key)) {
           if ($model = Menu::find()->where(['slug' => 'glavnoe-menu', 'is_active' => 1])->with('menuItems')->asArray()->one()) {
               $items = $this->menuItems($model['menuItems']);
               Yii::$app->cache->set($cache_key, $items, 0, new \yii\caching\TagDependency(['tags' => 'menu']));
           }
        }
        return $this->render('main', ['items' => $items ? $items : []]);
    }
    
    /**
     * Меню с иерархической структурой
     * @return array 
     */
    protected function menuItems($data)
    {
        foreach ($data as $value){
            if($value['parent_id'] == 0){
                $this->items_list[$value['id']] = $this->createLink($value);
                $this->dropDownTree($data, $value['id']);
            }
        }
        return $this->items_list;
    }

    /**
     * Построение элементов иерархической структуры
     * @param array $array данные пунктов меню
     * @param integer $parent_id ID родительского пункта меню
     * @param integer $level уровень пункта в меню
     */
    protected function dropDownTree($array, $parent_id, $level = 1)
    {
        foreach ($array as $item){
            if($item['parent_id'] == $parent_id){
                $this->items_list[$parent_id]['items'][] = $this->createLink($item);
                $this->dropDownTree($array, $item['id'], $level+1);
            }
        }
    }
    
    /**
     * Создание ссылки
     * @param array $item данные пункта меню
     * @return array 
     */
    protected function createLink($item)
    {
        $visible = [];
        $options = [];
        $link = [
            'label' => (!empty($item['before_link']) ? $item['before_link'] : '') . $item['title'] . (!empty($item['after_link']) ? $item['after_link'] : ''),
            'url' => !empty($item['link']) ? $item['link'] : [$this->linkUrl($item['page_id'])]
        ];
        if (!Yii::$app->user->isGuest && $item['only_guest_show']) {
            $visible = ['visible' => false];
        }
        if (Yii::$app->user->isGuest && $item['guest_not_show']) {
            $visible = ['visible' => false];
        }
        if (!empty($item['options']) && strpos($item['options'], '=>')) {
            $options = ['linkOptions' => CmsHelper::settings_array($item['options'])];
        }
        return $link + $visible + $options;
    }
    
    /**
     * Создание URL ссылки
     * @param integer $id ID страницы в структуре сайта
     * @param string $url адрес страницы
     * @return string 
     */
    protected function linkUrl($id, $url = '')
    {
        $page = Pages::find()->select('parent_id,url')->where(['id' => $id])->asArray()->one();

        if (empty($page['url'])) {
            return '/';
        } else {
            $url = $page['url'] . (!empty($url) ? '/' : '') . $url;
        }
        if ($page['parent_id'] == 1) {
            return '/' . $url;
        } else {
            return $this->linkUrl($page['parent_id'], $url);
        }
    }
}