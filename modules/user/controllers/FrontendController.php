<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\SignupForm;
use yii\web\BadRequestHttpException;
use app\modules\user\models\User;

class FrontendController extends \app\controllers\BaseFrontendController
{
    public $menu = [
        'user/frontend/login' => 'Вход',
        'user/frontend/remind' => 'Напомнить пароль',
        'user/frontend/reset' => 'Смена пароля',
        'user/frontend/registration' => 'Регистрация'
    ];
    
    public function actionLogin()
    {
        return Yii::$app->runAction('user/user/login', ['frontend' => true]);
    }
    
    public function actionRemind()
    {
        $this->view->params['breadcrumbs'][] = Yii::t('user', 'Восстановление доступа');
        
        return Yii::$app->runAction('user/user/remind', ['frontend' => true]);
    }
    
    public function actionReset($token = null)
    {
        $this->view->params['breadcrumbs'][] = Yii::t('user', 'Смена пароля');
        
        return Yii::$app->runAction('user/user/reset', ['token' => $token, 'frontend' => true]);
    }        
    
    public function actionRegistration()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if($user->activeAfterRegistration) {
                    if (Yii::$app->user->login($user)) {
                        return $this->goHome();
                    } 
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('user','Инструкции по завершению регистрации отправлены на Ваш e-mail.'));
                    $this->goHome();
                    Yii::$app->end();
                }
            }
        }
        $this->view->params['breadcrumbs'][] = Yii::t('user', 'Регистрация');
        
        return $this->render('registration', [
            'model' => $model
        ]);
    }
    
    public function actionAccauntActivation($token)
    {
        if (empty($token) || !is_string($token)) {
            throw new BadRequestHttpException(Yii::t('user','Токен не может быть пустым.'));
        } else {
            $model = User::findOne(['auth_key' => $token]);
        }
        if (!$model || !$model->validateAuthKey($token)) {
            throw new BadRequestHttpException(Yii::t('user','Неправильный токен.'));
        } elseif ($model->is_active > 0) {
            throw new BadRequestHttpException(Yii::t('user','Ваш аккаунт уже активирован.'));
        } else {
            $model->is_active = 1;
            if($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('user','Ваш аккаунт активирован.'));
                return $this->goHome();
            }
        }
    }
}
?>