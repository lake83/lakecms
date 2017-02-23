<?php
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = $model->id == Yii::$app->user->id ? 'Профиль' : 'Редактирование пользователя: ' . ' ' . $model->id;

echo $this->render('_form', ['model' => $model]) ?>