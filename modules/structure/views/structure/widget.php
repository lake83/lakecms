<div class="row">
    <div class="col-sm-5">
    <?=yii\bootstrap\Nav::widget([
        'id' => 'modules',
        'items' => $items
    ])?>
    </div>
	<div id="actions" class="col-sm-7">
    </div>
</div>
<?php
$request = Yii::$app->request;

$this->registerJs(
"$('#modules li a').click(function(){
    $.post($(this).attr('data-menu'), function(data){ $('#actions').html(data) });
    return false;
});
function saveAction(url) {
    $.post('/structure/structure/widget', 
        {'action' : url, 'id' : '".$request->get('id')."', 'page_id' : ".$request->get('page_id')."}, 
        function(){ location.reload() }
    );
}");	
?>