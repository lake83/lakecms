<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Редактирование: ' . $model->message;

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?php foreach ($model->messages as $language => $message)

    if ($language !== Yii::$app->sourceLanguage)
        echo $form->field($model->messages[$language], '[' . $language . ']translation')->label(Yii::$app->params['languages'][$language]); ?>

    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>