<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class AdminController extends Controller
{
    public $layout = '@app/modules/admin/views/layouts/main';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public static function is_active($searchModel)
    {
        return [
            'attribute' => 'is_active',
            'filter' => Html::activeDropDownList(
            $searchModel,
            'is_active',
            [0 => 'Не активно', 1 => 'Активно'],
                ['class' => 'form-control', 'prompt' => '- выбрать -']
            ),
            'value' => function ($model, $index, $widget) {
                return $model->is_active == 1 ? 'Активно' : 'Не активно';}
        ];
    }
}