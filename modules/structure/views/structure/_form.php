<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'url', ['enableAjaxValidation' => true]) ?>
        
    <?= $form->field($model, 'title') ?>
            
    <?= $form->field($model, 'seo_key') ?>
            
    <?= $form->field($model, 'seo_description')->textArea() ?>
            
    <?= $form->field($model, 'layout')->dropDownList(Yii::$app->params['page_layouts'], ['class' => 'form-control', 'prompt' => '- выбрать -']) ?>
            
    <?php if ($model->isNewRecord)
              echo $form->field($model, 'parent_id')->hiddenInput()->label(false)->error(false) ?>
    
    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>