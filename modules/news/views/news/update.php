<?php

/* @var $this yii\web\View */
/* @var $model app\modules\news\models\News */

$this->title = 'Редактирование новости: ' . $model->title;

echo $this->render('_form', ['model' => $model]) ?>