<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Вход';

$this->registerJs("jQuery('#modal').modal('show');$('#modal').on('hidden.bs.modal', function () {location.href='".Yii::$app->homeUrl."'})");
?>
<?php
Modal::begin([
    'header' => '&nbsp;',
    'id' => 'modal',
    'size' => 'modal-sm'
]); ?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form'
    ]); ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <div class="form-group">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
       
<?php Modal::end(); ?>