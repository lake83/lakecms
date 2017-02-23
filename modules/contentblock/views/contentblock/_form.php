<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'title') ?>
    
    <?=$form->field($model, 'text')->widget(\app\components\RedactorTinymce::className())?>
    
    <?= $form->field($model, 'js')->textArea() ?>
    
    <?= $form->field($model, 'css')->textArea() ?>
            
    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>