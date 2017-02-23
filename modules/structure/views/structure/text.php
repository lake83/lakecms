<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(['id' => 'text-form', 'layout' => 'horizontal']) ?>
        
<?=$form->field($model, 'contentblock_id')->dropDownList($menu['block'], ['prompt'=>'выбрать']) ?>

<?=$form->field($model, 'layout')->dropDownList($menu['layout'], ['prompt'=>'выбрать']) ?>

<?=$form->field($model, 'position')->hiddenInput()->label(false) ?>

<?=$form->field($model, 'page_id')->hiddenInput()->label(false) ?>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    
<?php ActiveForm::end();

$this->registerJs(
"$('body').on('beforeSubmit', '#text-form', function () {
    var form = $(this);
    if (form.find('.has-error').length) {
        return false;
    }
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function(data){ location.reload() }
    });
    return false;
});", \yii\web\View::POS_END); ?>