<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->module->title.' → Переводы в моделях → Записи';
  
Pjax::begin();
    
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{pager}',
        'columns' => array_merge(
            [['class' => 'yii\grid\SerialColumn']],
            
            $columns,
            
            [[
                'class' => 'yii\grid\ActionColumn',
                'template' => '{translate}{delete}',
                'options' => ['width' => '50px'],
                'buttons' => [
                    'translate' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                               ['/translations/translate-models/translate',
                               'module_id' => Yii::$app->request->get('module_id'), 'model_id' => Yii::$app->request->get('model_id'), 'id' => $model['id']],
                               ['title' => 'Переводы', 'data-pjax' => '0']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                               ['/translations/translate-models/delete',
                               'module_id' => Yii::$app->request->get('module_id'), 'model_id' => Yii::$app->request->get('model_id'), 'id' => $model['id']],
                               ['title' => 'Удалить', 'data-pjax' => '0', 'data-confirm' => 'Вы уверены, что хотите удалить переводы?', 'data-method' => 'post']);
                    }
                ]
            ]]
        )
    ]);
    
Pjax::end(); ?>