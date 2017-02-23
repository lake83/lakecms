<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\CmsHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\event\models\MailQueueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->module->title;
?>

<p><?= Html::a('Удалить все', ['delete-all'], ['class' => 'btn btn-danger', 'data-method' => 'post', 'data-confirm' => 'Вы действительно хотите удалить все письма?']) ?></p>

<?php Pjax::begin();

echo GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            'subject',
            'to',
            CmsHelper::created_at($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{delete}',
                'options' => ['width' => '50px']
            ]
        ],
    ]);

Pjax::end(); ?>