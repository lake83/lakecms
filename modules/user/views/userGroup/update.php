<?php
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\UserGroup */

$this->title = 'Редактирование групы пользователя: ' . ' ' . $model->id;
?>
<h1><?=$this->title?></h1>

<?= $this->render('_form', ['model' => $model]) ?>