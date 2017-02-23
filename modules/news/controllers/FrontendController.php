<?php

namespace app\modules\news\controllers;

use Yii;
use app\modules\news\models\News;
use app\modules\news\models\NewsSearch;
use yii\web\NotFoundHttpException;

/**
 * FrontendController implements the frontend actions for News module.
 */
class FrontendController extends \app\controllers\BaseFrontendController
{
    public $menu = [
        'news/frontend/news' => 'Все новости',
        'news/frontend/article' => 'Новость'
    ];
    
    public function actionNews()
    {
        $searchModel = new NewsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $this->view->params['breadcrumbs'][] = Yii::t('news', 'Новости');
        
        return $this->render('news', ['dataProvider' => $dataProvider]);
    }
    
    public function actionArticle($slug = null)
    {
        if (is_null($slug)) {
            return '';
        }
        if (!$model = News::find()->where(['slug' => $slug, 'is_active' => 1])->localized()->asArray()->one()) {
            throw new NotFoundHttpException(Yii::t('app', 'Страница не найдена.'));
        } 
        $view = $this->view;
        $view->title = $model['title'];
        $view->keywords = $model['seo_key'];
        $view->description = $model['seo_description'];
        $view->params['breadcrumbs'][] = ['label' => Yii::t('news', 'Новости'), 'url' => ['/news']];
        $view->params['breadcrumbs'][] = $model['title'];
        
        return $this->render('article', ['model' => $model]);
    }
}