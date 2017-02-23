<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\CmsHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\menu\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->module->title;
?>

<p><?= Html::a('Создать меню', ['create'], ['class' => 'btn btn-success']) ?></p>

<?php Pjax::begin();

echo GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            'title',
            'slug',
            CmsHelper::is_active($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'options' => ['width' => '50px']
            ]
        ]
    ]);

Pjax::end(); ?>