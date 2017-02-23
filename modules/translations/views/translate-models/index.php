<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */

$this->title = $this->context->module->title.' → Переводы в моделях';

Pjax::begin();
    
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}{pager}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'module',
                'label' => 'Модуль',
                'value' => function ($model, $index, $widget) {
                    return $model['module'];}
            ],
            [
                'attribute' => 'model',
                'label' => 'Модель',
                'value' => function ($model, $index, $widget) {
                    return $model['model'];}
            ],
            [
                'attribute' => 'attributes',
                'label' => 'Атрибуты',
                'value' => function ($model, $index, $widget) {
                    return $model['attributes'];}
            ],
            
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{list}',
                'options' => ['width' => '50px'],
                'buttons' => [
                    'list' => function ($url, $model) {
                        return Html::a('<i class="fa fa-list"></i>',
                               ['/translations/translate-models/list', 'module_id' => $model['module_id'], 'model_id' => $model['model_id']],
                               ['title' => 'Записи', 'data-pjax' => '0']);
                    }
                ]
            ]
        ],
    ]);
    
Pjax::end(); ?>