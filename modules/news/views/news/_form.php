<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\FilemanagerInput;

/* @var $this yii\web\View */
/* @var $model app\modules\news\models\News */
/* @var $form yii\bootstrap\ActiveForm */

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'image')->widget(FilemanagerInput::className());?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?php if (!$model->isNewRecord) 
        echo $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?=$form->field($model, 'text')->widget(\app\components\RedactorTinymce::className())?>

    <?= $form->field($model, 'seo_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>