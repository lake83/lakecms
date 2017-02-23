<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\CmsHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\contentblock\models\Contentblock */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->module->title;
?>

<p><?= Html::a('Создать контекстный блок', ['create'], ['class' => 'btn btn-success']) ?></p>

<?php Pjax::begin();
    
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{pager}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
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