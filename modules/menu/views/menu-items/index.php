<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\CmsHelper;
use app\modules\menu\models\Menu;
use app\modules\menu\models\MenuItems;
use app\modules\structure\models\Pages;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\menu\models\MenuItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->module->title;
$listOptions = ['class' => 'form-control', 'prompt' => '- выбрать -'];
?>

<p><?= Html::a('Создать пункт меню', ['create'], ['class' => 'btn btn-success']) ?></p>

<?php Pjax::begin();

echo GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'menu_id',
                'filter' => Html::activeDropDownList($searchModel, 'menu_id', Menu::getAll(), $listOptions),
                'value' => function ($model, $index, $widget) {
                    return $model->menu->title;}
            ],
            [
                'attribute' => 'parent_id',
                'filter' => Html::activeDropDownList($searchModel, 'parent_id', MenuItems::getAll(), $listOptions),
                'value' => function ($model, $index, $widget) {
                    return $model->parent_id === 0 ? '---' : $model->getAll()[$model->parent_id];}
            ],
            [
                'attribute' => 'page_id',
                'filter' => Html::activeDropDownList($searchModel, 'page_id', Pages::treeSelect(), $listOptions),
                'value' => function ($model, $index, $widget) {
                    return $model->page_id === 0 ? '---' : Pages::treeSelect()[$model->page_id];}
            ],
            CmsHelper::is_active($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'options' => ['width' => '50px']
            ]
        ]
    ]);

Pjax::end(); ?>