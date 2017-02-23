<?php
use app\components\CmsHelper;

/* @var $this yii\web\View */
/* @var $model \app\modules\news\models\News */
?>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <img width="100%" src="<?=CmsHelper::resized_image($model['image'], 340, 200)?>" alt="<?=$model['title']?>" />
    </div>
    <div class="col-sm-6 col-md-8">
        <h1><?=$model['title']?></h1>
        <span class="label label-info"><?=Yii::$app->formatter->asDate($model['created_at'], 'php:d F Y')?></span>
        <hr />
        <?=$model['text']?>
    </div>
</div>