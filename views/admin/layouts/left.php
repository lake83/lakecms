<?php

use yii\bootstrap\Modal;
use kartik\cmenu\ContextMenu;
use dmstr\widgets\Menu;
use app\components\CmsHelper;

if ($this->context->module->id == 'structure') {
    Modal::begin([
        'header' => '<h2><span id="modalPageTitle"></span></h2>',
        'id' => 'modal_page'
    ]);
    echo '<div id="modalPageContent"></div>';

    Modal::end();
} ?>
<aside class="main-sidebar">
    <section class="sidebar">
<?php
if ($this->context->module->id == 'structure') {
    ContextMenu::begin([
        'id' => 'actions',
        'encodeLabels' => false,
        'pluginOptions' => [
            'before' => "
                function(e,context) {
                    var id = $(e.target.closest('a')).attr('href').split('id=')[1];
                    $('#actions').attr('data-id', id === undefined ? 0 : id);
                    return true;
            }",
            'onItem' => "
                function(context,e) {
                    e.preventDefault();
                    var id = $(context).attr('data-id');               
                    if($(e.target).attr('href') == '/structure/structure/delete') {
                        if(id == 1 || !confirm('".Yii::t('app', 'Вы уверены, что хотите удалить этот элемент?')."'))
                            return false;
                        else
                            $.post('/structure/structure/delete?id=' + id, function() {location.href='/structure/structure/index'});
                    } else {
                    $.ajax({
                        url: $(e.target).attr('href'),
                        type: 'get',
                        data: {id: id},
                        success: function(data){
                            $('#modalPageContent').html(data);
                            if($('#pages-parent_id').length !== 0)
                                $('#pages-parent_id').val(id);
                            $('#modal_page').modal('show').find('#modalPageTitle').text($(e.target).text());
                        },
                        error: function (jqXHR) {
                            $('#modalPageContent').html(jqXHR.responseText);
                            $('#modal_page').modal('show').find('#modalPageTitle').text(jqXHR.status);
                        }
                    })};
                }"
        ],
        'items'=>[
            ['label'=>'<i class="fa fa-plus"></i> '.Yii::t('app', 'Добавить'), 'url' => ['structure/create']],
            ['label'=>'<i class="fa fa-trash"></i> '.Yii::t('app', 'Удалить'), 'url' => ['structure/delete']],
            ['label'=>'<i class="fa fa-pencil"></i> '.Yii::t('app', 'Свойства'), 'url' => ['structure/update']]
        ]
    ]);
}
if ($this->context->module->menu['modules_show'] == 1) {
    $items = [];
    foreach(Yii::$app->modules as $key => $module) {
        if (!is_object($module)) {
            $module = Yii::$app->getModule($key);
        }
        if (!empty($module->title) && $module->menu['modules_show'] == 1) {
            $items[] = $module->menu['items'][0];
        }   
    }
}

echo Menu::widget([
        'firstItemCssClass' => $this->context->module->id == 'structure' ? 'active' : '',
        'options' => ['class' => 'sidebar-menu'],
        'encodeLabels' => false,
        'items' => CmsHelper::is_item_visible($this->context->module->menu['modules_show'] == 1 ? $items : $this->context->module->menu['items'])
    ]);

if ($this->context->module->id == 'structure') {
    ContextMenu::end();
}	
?>
    </section>
</aside>