<?php

/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\MenuItems */

$this->title = 'Редактирование пункта меню: ' . $model->title;

echo $this->render('_form', ['model' => $model]) ?>