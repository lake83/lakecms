<?php
use kartik\cmenu\ContextMenu;
use yii\bootstrap\Modal;

Modal::begin([
    'header' => '<h2><span id="modalBlockTitle"></span></h2>',
    'id' => 'modal_block'
]);
echo '<div id="modalBlockContent"></div>';

Modal::end();

$page_id = Yii::$app->request->getQueryParam('id');
ContextMenu::begin([
    'id' => 'blocks',
    'encodeLabels' => false,
    'pluginOptions' => [
        'before' => "
            function (e,context) {
                var block = e.target.id;
                var parent = $(e.target).parents().attr('id');
                e.preventDefault();
                if (parent == 'blocks') {
                    e.preventDefault();
                    this.closemenu();
                    return false;
                }
                $('#blocks').attr('data-block', block.indexOf('@') == -1 ? parent : block);
                return true;
        }",
        'onItem' => "
            function(context,e) {
                e.preventDefault();
                var id = $(context).attr('data-block');
                if($(e.target).attr('href') == '/structure/structure/clear')
                    if(!confirm('Вы уверены, что хотите очистить этот блок?'))
                        return false;
                $.ajax({
                    url: $(e.target).attr('href') + '?id=' + id + '&page_id=".(isset($page_id) ? $page_id : 1)."',
                    type: 'post',
                    success: function(data){
                        if (data == 'OK')
                            location.reload();
                        else {
                            $('#modalBlockContent').html(data);
                            $('#modal_block').modal('show').find('#modalBlockTitle').text($(e.target).text());
                        }
                    },
                    error: function (jqXHR) {
                        $('#modalBlockContent').html(jqXHR.responseText);
                        $('#modal_block').modal('show').find('#modalBlockTitle').text(jqXHR.status);
                    }
                });
        }"
    ],
    'items'=>[
        ['label'=>'<i class="glyphicon glyphicon-font"></i> Контекстный блок', 'url' => ['structure/text']],
        ['label'=>'<i class="glyphicon glyphicon-modal-window"></i> Виджет', 'url' => ['structure/widget']],
        ['label'=>'<i class="glyphicon glyphicon-remove"></i> Очистить блок', 'url' => ['structure/clear']]
    ]
]);

echo $this->render('/layouts/'.$layout, ['scheme' => $scheme, 'use_blocks' => $use_blocks]);

ContextMenu::end();
?>