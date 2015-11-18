<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\user\models\UserGroup;
use app\widgets\CmsHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\user\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->module->title;
?>
<p><?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?></p>

<?php Pjax::begin();
    
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{pager}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'image',
                'format' => 'image',
                'value' => function ($model, $index, $widget) {
                    return $model->getThumbFileUrl('image', 'thumb', '/images/users/anonymous.png');}
            ],
            'name',
            'surname',
            'username',
            'email:email',
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
                'attribute' => 'created_at',
                'format' => 'date',
                'filter' => \yii\jui\DatePicker::widget([
                    'model'=>$searchModel,
                    'options' => ['class' => 'form-control'],               
                    'attribute'=>'created_at',
                    'language' => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                ])
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'options' => ['width' => '50px']
            ]
        ],
    ]);
    
Pjax::end(); ?>