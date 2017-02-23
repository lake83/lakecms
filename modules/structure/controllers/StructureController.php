<?php

namespace app\modules\structure\controllers;

use Yii;
use app\controllers\AdminController;
use app\modules\structure\models\Pages;
use yii\web\NotFoundHttpException;
use app\modules\contentblock\models\Contentblock;
use app\modules\contentblock\models\Blocks;
use app\components\CmsHelper;

/**
 * StructureController implements the CRUD actions for Pages model.
 */
class StructureController extends AdminController
{
    public function actions()
    {
        return [
            'create' => [
                'class' => $this->actionsPath.'Create',
                'model' => $this->modelPath.'Pages'
            ],
            'update' => [
                'class' => $this->actionsPath.'Update',
                'model' => $this->modelPath.'Pages'
            ],
            'delete' => [
                'class' => $this->actionsPath.'Delete',
                'model' => $this->modelPath.'Pages'
            ]
        ];
    }
    
    public function actionIndex($id = 1)
    {
        $model = Pages::findOne($id);
        
        if ($model == null) 
            throw new NotFoundHttpException('Страница не найдена.');
            
        $view = $this->view;
        $view->title = $model->title;
        $view->registerMetaTag(['name' => 'keywords', 'content' => $model->seo_key]);
        $view->registerMetaTag(['name' => 'description', 'content' => $model->seo_description]);
        
        return $this->render('index', ['layout' => $model->layout]);
    }
    
    public function actionPage($layout)
    {
        $this->layout = '@app/views/layouts/main';
        $this->view->registerCssFile('@web/css/admin_pages.css');
        $use_blocks = Blocks::find()->where(['page_id' => Yii::$app->request->getQueryParam('id')])->with('contentblock')->asArray()->all();
        
        return $this->render('/layouts/main', ['layout' => $layout, 'scheme' => true, 'use_blocks' => $use_blocks]);  
    }
    
    public function actionText()
    {
        $request = Yii::$app->request;
        $page_id = $request->get('page_id');
        $position = $request->get('id');
        
        if (!$model = $this->loadModel($page_id, $position))
        {
            $model = new Blocks;
            $model->page_id = $page_id;
            $model->position = $position;
        }   
        $model->type = 1;
        $model->scenario = 'contentblock';
        
        if ($model->load($request->post()) && $model->save())
            Yii::$app->session->setFlash('success', 'Контентный блок установлен.');
        else
        {
            return $this->renderAjax('text', ['model' => $model, 'menu' => [
                'block' => Contentblock::getAll(),
                'layout' => [
                    'empty' => 'Пустой (вывод только контента)',
                    'html' => 'Произвольный html'
                ]
            ]]);
        }
    }
    
    public function actionWidget()
    {
        $request = Yii::$app->request;
        $action = $request->post('action');
        if ($request->isAjax && !empty($action))
        {
            $page_id = $request->post('page_id');
            $position = $request->post('id');
            if (!$model = $this->loadModel($page_id, $position))
            {
                $model = new Blocks;
                $model->page_id = $page_id;
                $model->position = $position;
            }   
            $model->type = 2;
            $model->widget_action = $action;
            $model->save();
            Yii::$app->end();                        
        }
        $items = [];
        foreach(Yii::$app->modules as $key => $module)
        {
            if (!is_object($module))
                $module = Yii::$app->getModule($key);
            if (!empty($module->title) && is_file(Yii::getAlias('@app/modules/'.$key.'/controllers/FrontendController.php')))
                $items[] = ['label' => $module->title, 'url' => ['/'.$key.'/'.$key.'/index'], 'linkOptions' => ['data-menu' => '/'.$key.'/frontend/menu']];
        }
        return $this->renderAjax('widget', ['items' => CmsHelper::is_item_visible($items)]);
    }
    
    public function actionClear()
    {
        $request = Yii::$app->request;
        if ($request->isPost)
            if ($model = $this->loadModel($request->get('page_id'), $request->get('id')))
            {
                $model->delete();
                echo 'OK';
            }
            else
                throw new NotFoundHttpException('Запись не найдена.');
    }
    
    private function loadModel($page_id, $position)
    {
        return Blocks::find()->where(['page_id' => $page_id, 'position' => $position])->one();
    }        
}