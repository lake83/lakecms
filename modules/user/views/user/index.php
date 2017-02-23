<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\user\models\Group;
use app\components\CmsHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\user\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->module->title.' → Пользователи';
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
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return Html::img(CmsHelper::resized_image($model->image, 70, 70), ['width' => 70]);
                }
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
                    Group::getAll(),
                    ['class' => 'form-control', 'prompt' => '- выбрать -']
                ),
                'value' => function ($model, $index, $widget) {
                    return $model->group->title;}
            ],
            CmsHelper::is_active($searchModel),
            CmsHelper::created_at($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'buttons'=>[
                    'delete' => function ($url, $model) {     
                        return $model->id == 1 ? '' : Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => 'Удалить',
                            'data-method' => 'POST',
                            'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?'
                        ]);
                    }
                ],
                'options' => ['width' => '50px']
            ]
        ],
    ]);
    
Pjax::end(); ?>