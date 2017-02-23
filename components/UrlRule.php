<?php
namespace app\components;

use Yii;
use app\modules\structure\models\Pages;
 
class UrlRule extends \yii\web\UrlRule
{
    /**
     * экшн для обработки всех запросов
     */
    const DEFULT_ACTION = 'site/index';
    /**
     * @inheritdoc
     */
    public $route = '';
    /**
     * @inheritdoc
     */
    public $pattern = '';
    
    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route == self::DEFULT_ACTION) {
            return '';
        } else {
            return parent::createUrl($manager, $route, $params);
        }     
    }

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        $page = true;
        $lang = Yii::$app->language;
        
        if ($pathInfo = $request->getPathInfo()) {
        
            $path = explode('/', $pathInfo);
                        
            if (count($path) == 1) { //страница с одним элементом при делении по /
                if (Yii::$app->cache->get($path[0] . '__' . $lang) === false) {
                    $page = $this->pageCache($path[0]);
                }
            } else { //страница с несколькими элементами при делении по /
                foreach($manager->rules as $rule) {
                    if (!empty($rule->pattern) && preg_match($rule->pattern, rtrim($pathInfo, '/'), $matches)) {
                        foreach($matches as $key => $value) {
                            if (isset($rule->placeholders[$key])) {
                                $matches[$rule->placeholders[$key]] = $value; 
                            }
                            unset($matches[$key]); 
                        }
                                                   
                        if (Yii::$app->cache->get($rule->route . '__' . $lang) === false) {
                            $path = explode('/', $rule->route);
                            if ($parent = Pages::find()->select('id')->where(['url' => $path[sizeof($path)-2], 'is_active' => 1])->asArray()->one()) {
                                $this->pageCache($rule->route, $parent['id'], end($path));
                            } 
                        }
                        
                        return [self::DEFULT_ACTION, ['page_url' => $rule->route] + $matches];
                    }
                }                                          
                if (Yii::$app->cache->get($pathInfo . '__' . $lang) === false) {
                    if ($parent = Pages::find()->select('id')->where(['url' => $path[sizeof($path)-2], 'is_active' => 1])->asArray()->one()) {
                        $page = $this->pageCache($pathInfo, $parent['id'], end($path));
                    } else {
                        $page = false;
                    }
                }  
            }
        } else { //главная страница
            if (Yii::$app->cache->get(self::DEFULT_ACTION . '__' . $lang) === false) {
                $page = $this->pageCache(1);
            } 
            $pathInfo = self::DEFULT_ACTION;
        }
        return $page ? [self::DEFULT_ACTION, ['page_url' => $pathInfo]] : false;
    }
    
    /**
     * Добавление страницы в кеш
     * @param integer|string $condition ID если главная или путь к странице в структуре сайта
     * @param integer $parent ID родительской страницы
     * @param string $url ссылка страницы
     * @return boolean true если кеш установлен
     */
    protected function pageCache($condition, $parent = null, $url = null)
    {
        if (is_null($parent) && is_null($url)) {
            $model = Pages::find()->where([is_numeric($condition) ? 'id' : 'url' => $condition, 'is_active' => 1])->with('blocks')->localized()->asArray()->one();
            if ($model['parent_id'] > 1) { //если страница в структуре выше второго порядка возвращаем false, нельзя обращатся напрямую, только parent/page
                return false;
            }
        } else {
            $path = array_reverse(explode('/', $condition));
            if ($this->pageExist($path, $parent)) { //если url не совпадает со структурой сайта возвращаем false
                $model = Pages::find()->where(['parent_id' => $parent, 'url' => $url, 'is_active' => 1])->with('blocks')->localized()->asArray()->one();
            }
        }
        
        if ($model) {
            //устанавливаем кеш с параметрами страницы и ключем ее url 
            $cache_key = (is_numeric($condition) ? self::DEFULT_ACTION : $condition) . '__' . Yii::$app->language;
            Yii::$app->cache->set($cache_key, $model, 0, new \yii\caching\TagDependency(['tags' => 'pages']));
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Проверяет существование страницы в структуре сайта
     * @param array $path url
     * @param integer $parent_id ID родительской страницы
     * @return boolean true если путь правильный
     */
    protected function pageExist($path, $parent_id)
    {
        foreach($path as $key => $value) {
            $parent = Pages::find()->select('parent_id,url')->where(['id' => $parent_id])->asArray()->one();
            if ($parent['url'] == $path[$key+1]) {
                $parent_id = $parent['parent_id'];                
            } else {
                return false;
            }
        }
        return true;
    }
}