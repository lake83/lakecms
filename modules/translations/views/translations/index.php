<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\translations\models\SourceMessage */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->module->title.' → Переводы сообщений';

echo Html::a('<i class="fa fa-refresh"></i> Поиск сообщений', ['scan'], ['class' => 'btn btn-default', 'data' => [
         'confirm' => 'Будут сканироватся все файлы проекта, это может занять некоторое время.']
     ]);
Pjax::begin();
    echo GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'layout' => '{items}{pager}',        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
                        
            'message',
            [
                'attribute' => 'category',
                'value' => function ($model, $index, $dataColumn) {
                    return $model->category;
                },
                'filter' => ArrayHelper::map($searchModel::getCategories(), 'category', 'category')
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    /** @var SourceMessage $model */
                    return $model->isTranslated() ? 'Переведено' : '<i class="text-danger">Не переведено</i>';
                },
                'filter' => $searchModel->getStatus()
            ],
            
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'options' => ['width' => '50px']
            ]
        ]
    ]);
Pjax::end(); ?>