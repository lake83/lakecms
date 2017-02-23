<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\menu\models\Menu;
use app\modules\structure\models\Pages;

/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\MenuItems */
/* @var $form yii\bootstrap\ActiveForm */

$listOptions = ['class' => 'form-control', 'prompt' => '- выбрать -'];

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'menu_id')->dropDownList(Menu::getAll(), $listOptions) ?>
    
    <?= $form->field($model, 'parent_id')->dropDownList($model->getAll($model->menu_id), $listOptions) ?>
    
    <?= $form->field($model, 'page_id')->dropDownList(Pages::treeSelect(), $listOptions) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'options')->textInput(['maxlength' => true])
        ->hint('вида: атрибут=>значение, class=>class_title, style=>width:100px;, data-method=>post, target=>_blank ...') ?>

    <?= $form->field($model, 'before_link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'after_link')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'only_guest_show')->checkbox() ?>
    
    <?= $form->field($model, 'guest_not_show')->checkbox() ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>