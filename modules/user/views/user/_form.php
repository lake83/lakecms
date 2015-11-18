<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\user\models\UserGroup;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
    
        <div class="col-lg-6">
    
            <?php echo $form->field($model, 'image')->fileInput(); 
                if (!$model->isNewRecord)
                    echo Html::img(Yii::$app->user->identity->getThumbFileUrl('image', 'thumb', '/images/users/anonymous.png'));
            ?>
            
            <?= $form->field($model, 'name') ?>
            
            <?= $form->field($model, 'surname') ?>
            
            <?= $form->field($model, 'username') ?>
            
            <?= $form->field($model, 'password_hash')->passwordInput() ?>
            
            <?= $form->field($model, 'email') ?>
            
            <?= $form->field($model, 'status')->dropDownList(UserGroup::getAll(), ['class' => 'form-control', 'prompt' => '- выбрать -']) ?>
            
            <?= $form->field($model, 'is_active')->checkbox() ?>
            
        </div>
    
    </div>    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>