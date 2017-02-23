<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\bootstrap\ActiveForm;

$this->title = 'Редактирование перевода модели';

$form = ActiveForm::begin(['layout' => 'horizontal']);

$items[] = [
    'label' => 'Запись',
    'content' => $this->render('_tab', ['form' => $form, 'owner' => $owner, 'translations' => $translations]),
    'active' => true
];

foreach(Yii::$app->params['languages'] as $key => $lang) {
    if ($key !== Yii::$app->sourceLanguage) {
        $items[] = [
            'label' => $lang,
            'content' => $this->render('_tab', ['form' => $form, 'model' => $model, 'lang' => $key, 'translations' => $translations])
        ];
    }
}
?>
<div class="nav-tabs-custom">
    <?= Tabs::widget(['items' => $items])?>
</div>

<div class="box-footer">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>