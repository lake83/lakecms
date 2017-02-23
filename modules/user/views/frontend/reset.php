<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\ResetForm */

$this->title = Yii::t('user','Смена пароля');
?>
<div class="site-reset">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-form']); ?>
    
            <?= $form->field($model, 'password')->passwordInput() ?>
    
             <div class="form-group">
                 <?= Html::submitButton(Yii::t('user', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
             </div>
    
             <?php ActiveForm::end(); ?>
                 
             <?= Html::a(Yii::t('user', 'Вход'), ['/login']) ?>
        </div>
    </div>
</div>