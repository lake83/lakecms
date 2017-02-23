<?php
use yii\helpers\Html;
use app\components\CmsHelper;

/* @var $this \yii\web\View */
/* @var $content string */

$avatar = CmsHelper::resized_image(Yii::$app->user->identity->image, 70, 70);
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <?php if ($modules = app\components\CmsHelper::modules_link()): ?>
                <li><?= Html::a('<i class="fa fa-th-large"></i> <span class="hidden-xs">'.Yii::t('app', 'Модули').'</span>', $modules, ['title' => Yii::t('app', 'Модули'), 'class' => 'label dropdown-toggle']) ?></li>
                <?php endif; ?>
                
                <li><?= Html::a('<i class="fa fa-envelope-o"></i> <span class="hidden-xs">'.Yii::t('app', 'События').'</span>', ['/event/mail-templates/index'], ['title' => Yii::t('app', 'События'), 'class' => 'label dropdown-toggle']) ?></li>
                
                <li><?= Html::a('<i class="fa fa-user"></i> <span class="hidden-xs">'.Yii::t('app', 'Доступ').'</span>', ['/user/user/index'], ['title' => Yii::t('app', 'Доступ'), 'class' => 'label dropdown-toggle']) ?></li>
                
                <li><?= Html::a('<i class="fa fa-share-alt"></i> <span class="hidden-xs">'.Yii::t('app', 'Структура').'</span>', ['/structure/structure/index', 'id' => 1], ['title' => Yii::t('app', 'Структура'), 'class' => 'label dropdown-toggle']) ?></li>

                <li><?= Html::a('<i class="fa fa-server"></i> <span class="hidden-xs">'.Yii::t('app', 'Меню').'</span>', ['/menu/menu/index'], ['title' => Yii::t('app', 'Меню'), 'class' => 'label dropdown-toggle']) ?></li>
                
                <?php $name = !empty(Yii::$app->user->identity->name) && !empty(Yii::$app->user->identity->surname) ? Yii::$app->user->identity->name.' '.Yii::$app->user->identity->surname : Yii::$app->user->identity->username ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?=$avatar?>" class="user-image" alt="<?=$name?>"/>
                        <span class="hidden-xs"><?=$name?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?=$avatar?>" class="img-circle" alt="<?=$name?>"/>
                            <p>
                                <?=$name.' - '.Yii::$app->user->identity->group->title?>
                                <small>Зарегистрирован: <b><?=Yii::$app->formatter->asDate(Yii::$app->user->identity->created_at, 'long')?></b></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(Yii::t('app', 'Профиль'), ['/user/user/update', 'id' => Yii::$app->user->id], ['class' => 'btn btn-default btn-flat']) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(Yii::t('app', 'Выход'), ['/user/user/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']) ?>
                            </div>
                        </li>
                    </ul>
                </li>
                
                <li><?= Html::a('<i class="fa fa-home"></i>', '/', ['title' => Yii::t('app', 'Сайт'), 'class' => 'label dropdown-toggle', 'style' => 'font-size:120%', 'target' => '_blank']) ?></li>
                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>