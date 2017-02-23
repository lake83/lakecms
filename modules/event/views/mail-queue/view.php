<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\event\models\MailQueue */

$this->title = $this->context->module->title;
?>

<h1>Письмо #<?= $model->id ?></h1>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'subject',
        'text:html',
        'to:email',
        'created_at:date',
    ],
]) ?>