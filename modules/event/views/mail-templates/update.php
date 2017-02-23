<?php

/* @var $this yii\web\View */
/* @var $model app\modules\event\models\MailTemplates */

$this->title = 'Редактирование шаблонов почтовых событий: ' . $model->title;

echo $this->render('_form', ['model' => $model]) ?>