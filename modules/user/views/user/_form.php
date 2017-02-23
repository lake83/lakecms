<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\models\Group;
use app\components\FilemanagerInput;

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>
    
    <?= $form->field($model, 'image')->widget(FilemanagerInput::className());?>
      
    <?= $form->field($model, 'name') ?>
            
    <?= $form->field($model, 'surname') ?>
            
    <?= $form->field($model, 'username') ?>
            
    <?php if ($model->id == Yii::$app->user->id) {
        echo $form->field($model, 'new_password')->passwordInput();
    } ?>
            
    <?= $form->field($model, 'email') ?>
            
    <?= $form->field($model, 'status')->dropDownList(Group::getAll(), ['class' => 'form-control', 'prompt' => '- выбрать -']) ?>
            
    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>