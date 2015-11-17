<?php
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\UserGroup */

$this->title = 'Создание групы пользователя';
?>
<h1><?=$this->title?></h1>

<?= $this->render('_form', ['model' => $model ]) ?>