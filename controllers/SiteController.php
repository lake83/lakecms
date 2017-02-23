<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [$this->action->id],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    return Yii::$app->response->redirect('index');
                }
            ]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
        ];
    }        
    
    public function actionIndex($page_url = null)
    {
        if (is_null($page_url) || !$model = Yii::$app->cache->get($page_url.'__'.Yii::$app->language))
            throw new NotFoundHttpException(Yii::t('app', 'Страница не найдена.'));
   
        $view = $this->view;
        $view->title = $model['title'];
        $view->keywords = $model['seo_key'];
        $view->description = $model['seo_description'];       
            
        return $this->render('index', ['layout' => $model['layout'], 'use_blocks' => $model['blocks']]);
    }
}