<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\user\models\UserGroup */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->module->title;
?>

<p><?= Html::a('Создать групу пользователей', ['create'], ['class' => 'btn btn-success']) ?></p>

<?php Pjax::begin();
    
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{pager}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'status',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    UserGroup::getAll(),
                    ['class' => 'form-control', 'prompt' => '- выбрать -']
                ),
                'value' => function ($model, $index, $widget) {
                    return !empty($model->group->title) ? $model->group->title : '';}
            ],
            CmsHelper::is_active($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'options' => ['width' => '50px']
            ]
        ],
    ]);
    
Pjax::end(); ?>