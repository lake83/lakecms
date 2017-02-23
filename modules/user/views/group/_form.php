<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\models\Group;
use yii\bootstrap\Tabs;
?>

<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <div class="nav-tabs-custom">
    <?= Tabs::widget([
            'items' => [
                [
                     'label' => 'Параметры',
                     'content' => $this->render('_params', ['model' => $model, 'form' => $form]),
                     'active' => true
                ],
                [
                     'label' => 'Разрешенные действия',
                     'content' => $this->render('_permissions', ['permissions' => $model->permissions])
                ]
            ]
    ]);?>
    </div>    

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>