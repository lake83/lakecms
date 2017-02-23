<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\CmsHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\news\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->module->title;
?>

<p><?= Html::a('Создать новость', ['create'], ['class' => 'btn btn-success']) ?></p>

<?php Pjax::begin();

echo GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return Html::img(CmsHelper::resized_image($model->image, 70, 70), ['width' => 70]);
                }
            ],
            'title',
            'slug',
            CmsHelper::is_active($searchModel),
            CmsHelper::created_at($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'options' => ['width' => '50px']
            ]
        ],
    ]);

Pjax::end(); ?>