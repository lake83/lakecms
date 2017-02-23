<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\event\models\MailTemplates */
/* @var $form yii\bootstrap\ActiveForm */

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?php if (!$model->isNewRecord) 
        echo $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
        
    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'text')->widget(\app\components\RedactorTinymce::className()) ?>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="help-block col-sm-6">Переменные указать в тексте шаблона в виде [var].</div>
    </div>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>