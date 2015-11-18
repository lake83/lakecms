<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\widgets\LoginForm;

class AdminController extends Controller
{
    public $layout = '@app/modules/admin/views/layouts/main';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['login'],
                'rules' => [
                    [
                        'actions' => [$this->action->id],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'logout' => ['post']
                ],
            ],
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
    
    public function actionLogin()
    {
        $this->layout = 'main-login';
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }                

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['admin/index']);
        } else {
            return $this->render('login', [
                'model' => $model
            ]);
        }
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }
}