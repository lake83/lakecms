<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Inflector;

Modal::begin([
    'header' => '<h2><span id="modalTitle"></span></h2>',
    'id' => 'modal'
]);

echo '<div id="modalContent"></div>';

Modal::end();
?>
<div class="content-wrapper"<?=Yii::$app->errorHandler->exception == null ? '' : ' style="margin-left:0"'?>>
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo Html::encode($this->title);
                } else {
                    echo Inflector::camel2words(Inflector::id2camel($this->context->module->id));
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                }
                if (Yii::$app->controller->module->id == 'structure') {
                    echo Html::a(' <i class="fa fa-info-circle"></i>', 'javascript:void(0)', [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'bottom',
                        'title' => Yii::t('app', 'Для управления структурой сайта или блоками на странице используйте контекстное меню, вызваное правой кнопкой мыши.'),
                        'class' => 'info'
                    ]);
                    $this->registerJs("$('[data-toggle=\"tooltip\"]').tooltip();");
                }?>
            </h1>
        <?php } ?>

        <?=Breadcrumbs::widget([
              'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
        ]) ?>
    </section>

    <section class="content box box-success">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<?php if (Yii::$app->errorHandler->exception == null): ?>
<footer class="main-footer">
    &copy; 2015 <a href="mailto:lake83@mail.ru">lake83@mail.ru</a>
</footer>
<?php endif; ?>

<aside class="control-sidebar control-sidebar-dark">

    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-wrench"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-database"></i></a></li>
    </ul>
    
    <div class="tab-content">
        <div class="tab-pane active" id="control-sidebar-home-tab">
            <h3 class="control-sidebar-heading">
                <?=Yii::t('app', 'Настройки')?>
                <a class="btn btn-success pull-right" href="#" onclick="js:createSetting('<?=Yii::t('app', 'Создать настройку')?>');return false;"><?=Yii::t('app', 'Создать')?></a>
            </h3>
            <ul class='control-sidebar-menu'>
                <?php 
                $db = Yii::$app->db;
                $settings = $db->cache(function ($db) {
                    return (new \yii\db\Query())->from('settings')->all();
                }, 0, new \yii\caching\TagDependency(['tags' => 'settings']));
                foreach($settings as $param): ?>
                <li>
                    <a href="#" onclick="js:settings('<?=$param['label']?>','<?=$param['name']?>');return false;">
                        <i class="menu-icon fa <?=$param['icon']?> bg-green"></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading"><?=$param['label']?></h4>

                            <p id="<?=$param['name']?>"><?=$param['value']?></p>
                        </div>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <h3 class="control-sidebar-heading">
                <?=Yii::t('app', 'Очистить кеш')?>
            </h3>
            <a class="btn btn-danger" href="/admin/clear-cache" data-confirm="<?=Yii::t('app', 'Вы уверены, что хотите удалить весь кеш?')?>"><?=Yii::t('app', 'Очистить')?></a>
            
            <h3 class="control-sidebar-heading">
                <?=Yii::t('app', 'Очистить ресурсы (assets)')?>
            </h3>
            <a class="btn btn-danger" href="/admin/clear-assets" data-confirm="<?=Yii::t('app', 'Вы уверены, что хотите удалить все ресурсы?')?>"><?=Yii::t('app', 'Очистить')?></a>
        </div>
    </div>
</aside>
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>