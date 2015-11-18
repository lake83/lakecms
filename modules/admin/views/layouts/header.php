<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li><?= Html::a('<i class="fa fa-th-large"></i> <span class="hidden-xs">Модули</span>', ['/contentblock/contentblock/index'], ['title' => 'Модули', 'class' => 'label dropdown-toggle']) ?></li>
                
                <li><?= Html::a('<i class="fa fa-envelope-o"></i> <span class="hidden-xs">События</span>', ['/event/event/index'], ['title' => 'События', 'class' => 'label dropdown-toggle']) ?></li>
                
                <li><?= Html::a('<i class="fa fa-user"></i> <span class="hidden-xs">Доступ</span>', ['/user/user/index'], ['title' => 'Доступ', 'class' => 'label dropdown-toggle']) ?></li>
                
                <li><?= Html::a('<i class="fa fa-share-alt"></i> <span class="hidden-xs">Структура</span>', ['/structure/structure/index'], ['title' => 'Структура', 'class' => 'label dropdown-toggle']) ?></li>

                <li><?= Html::a('<i class="fa fa-server"></i> <span class="hidden-xs">Меню</span>', ['/structure/structure/index'], ['title' => 'Меню', 'class' => 'label dropdown-toggle']) ?></li>
                
                <?php $name = !empty(Yii::$app->user->identity->name) && !empty(Yii::$app->user->identity->surname) ? Yii::$app->user->identity->name.' '.Yii::$app->user->identity->surname : Yii::$app->user->identity->username ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= Yii::$app->user->identity->getThumbFileUrl('image', 'thumb', '/images/users/anonymous.png') ?>" class="user-image" alt="<?=$name?>"/>
                        <span class="hidden-xs"><?=$name?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= Yii::$app->user->identity->getImageFileUrl('image', '/images/users/anonymous.png') ?>" class="img-circle"
                                 alt="<?=$name?>"/>

                            <p>
                                <?=$name.' - '.Yii::$app->user->identity->group->title?>
                                <small>Зарегистрирован: <b><?=Yii::$app->formatter->asDate(Yii::$app->user->identity->created_at, 'long')?></b></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a('Профиль', ['/user/user/update', 'id' => Yii::$app->user->id], ['class' => 'btn btn-default btn-flat']) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a('Выход', ['/admin/admin/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>