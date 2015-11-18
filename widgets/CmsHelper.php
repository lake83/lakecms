<?php
namespace app\widgets;

use Yii;
use yii\helpers\Html;

class CmsHelper
{
    public static function is_active($searchModel)
    {
        return [
            'attribute' => 'is_active',
            'filter' => Html::activeDropDownList(
            $searchModel,
            'is_active',
            [0 => 'Не активно', 1 => 'Активно'],
                ['class' => 'form-control', 'prompt' => '- выбрать -']
            ),
            'value' => function ($model, $index, $widget) {
                return $model->is_active == 1 ? 'Активно' : 'Не активно';}
        ];
    }
}
?>