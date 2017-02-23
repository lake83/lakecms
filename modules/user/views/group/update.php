<?php
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\Group */

$this->title = 'Редактирование групы пользователей: ' . ' ' . $model->title;

echo $this->render('_form', ['model' => $model]) ?>