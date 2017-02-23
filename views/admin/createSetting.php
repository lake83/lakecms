<?php
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(['id' => 'createSettingForm', 'layout' => 'horizontal']);

echo $form->field($model, 'label')->label(Yii::t('app', 'Название'));

echo $form->field($model, 'value')->textArea()->label(Yii::t('app', 'Значение'));

echo $form->field($model, 'name')->label(Yii::t('app', 'Алиас'));

echo $form->field($model, 'icon')->label(Yii::t('app', 'Пиктограма'));

echo $form->field($model, 'rules')->label(Yii::t('app', 'Правило валидации'));

echo $form->field($model, 'hint')->textArea()->label(Yii::t('app', 'Примечание'));

echo \yii\helpers\Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary']);

ActiveForm::end(); ?>