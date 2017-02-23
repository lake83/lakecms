<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\RemindForm */

$this->title = Yii::t('user','Восстановление доступа');
?>
<div class="site-remind">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'remind-form']); ?>
    
            <?= $form->field($model, 'email') ?>
    
             <div class="form-group">
                 <?= Html::submitButton(Yii::t('user', 'Отправить'), ['class' => 'btn btn-primary']) ?>
             </div>
    
             <?php ActiveForm::end(); ?>
                 
             <?= Html::a(Yii::t('user', 'Вход'), ['/login']) ?>
        </div>
    </div>
</div>