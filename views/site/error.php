<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<h1><?= $name ?></h1>

<p><b><?= nl2br(Html::encode($message)) ?></b></p>

<p>
   <?= Yii::t('app','Данная ошибка произошла при обработке веб-сервером вашего запроса.
   Пожалуйста, свяжитесь с нами, если вы думаете, что это ошибка сервера. Спасибо.') ?>
</p>