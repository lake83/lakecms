<?php
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = 'Создание пользователя';
?>
<h1><?=$this->title?></h1>

<?= $this->render('_form', ['model' => $model ]) ?>