<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\components\LanguageDropdown;

/* @var $this yii\web\View */
/* @var $model \app\modules\menu\models\Menu */

NavBar::begin([
    'brandLabel' => 'My Company',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ]
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => $items + [
        [
            'label' => Yii::t('app', 'Язык'),
            'items' => LanguageDropdown::widget(),
            'visible' => is_array($langs = Yii::$app->params['languages']) && count($langs) > 1
        ]
    ]
]);
NavBar::end(); ?>