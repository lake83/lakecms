<?php

namespace app\modules\user\controllers;

use Yii;
use app\controllers\AdminController;
use yii\filters\AccessControl;
use app\modules\user\models\LoginForm;
use app\modules\user\models\RemindForm;
use app\modules\user\models\ResetForm;
use yii\base\InvalidParamException;
use app\components\CmsHelper;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminController
{
    public function behaviors()
    {
        return \yii\helpers\ArrayHelper::merge(['access' => ['except' => ['login','logout','remind','reset']]], parent::behaviors());
    }
    
    public function actions()
    {
        return [
            'index' => [
                'class' => $this->actionsPath.'Index',
                'search' => $this->modelPath.'UserSearch'
            ],
            'create' => [
                'class' => $this->actionsPath.'Create',
                'model' => $this->modelPath.'User',
                'scenario' => 'createUser'
            ],
            'update' => [
                'class' => $this->actionsPath.'Update',
                'model' => $this->modelPath.'User'
            ],
            'delete' => [
                'class' => $this->actionsPath.'Delete',
                'model' => $this->modelPath.'User'
            ],
            'toggle' => [
                'class' => \pheme\grid\actions\ToggleAction::className(),
                'modelClass' => $this->modelPath.'User',
                'attribute' => 'is_active'
            ]
        ];
    }
    
    public function actionLogin($frontend = false)
    {
        $this->layout = $this->layout($frontend);
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }                

        $model = new LoginForm;
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return !$frontend ? $this->redirect(CmsHelper::modules_link(true)) : $this->goHome();
        } else {
            return $this->render(!$frontend ? 'login' : '/frontend/login', ['model' => $model]);
        }
    }        
    
    /**
     * Запрос на восстановление пароля
     */
    public function actionRemind($frontend = false)
    {
        $this->layout = $this->layout($frontend);
        
        $model = new RemindForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail($frontend)) {
                Yii::$app->session->setFlash('success', Yii::t('user','Инструкции отправлены на Ваш e-mail.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('user','Ошибка при отправлении e-mail.'));
            }
            !$frontend ? $this->redirect(['/admin']) : $this->goHome();
            Yii::$app->end();
        }
        return $this->render(!$frontend ? 'remind' : '/frontend/remind', ['model' => $model]);
    }
    
    /**
     * Смена пароля
     */
    public function actionReset($token, $frontend = false)
    {
        $this->layout = $this->layout($frontend);
        
        try {
            $model = new ResetForm($token);
        } catch (InvalidParamException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            !$frontend ? $this->redirect(['/admin']) : $this->goHome();
            Yii::$app->end();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('user','Новый пароль сохранен.'));
            !$frontend ? $this->redirect(['/admin']) : $this->goHome();
            Yii::$app->end();
        }

        return $this->render(!$frontend ? 'reset' : '/frontend/reset', ['model' => $model]);
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    private function layout($frontend)
    {
        return !$frontend ? '@app/views/admin/layouts/main-login' : false;
    }
}