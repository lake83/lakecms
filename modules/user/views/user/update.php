<?php
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = 'Редактирование пользователя: ' . ' ' . $model->id;

echo $this->render('_form', ['model' => $model]) ?>