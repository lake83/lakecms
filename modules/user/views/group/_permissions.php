<?php 
use yii\helpers\Html;
?>

<div class="box box-body">
<?php
echo Html::checkBox('set-clear', false, [
        'id' => 'set-clear',
        'onclick' => 'js:checkAll(this.id)',
        'label' => 'Установить / Снять всё',
        'labelOptions' => [
            'class' => 'checkbox-inline'
        ]
]);
echo Html::checkBox('set-clear-index', false, [
        'id' => 'set-clear-index',
        'onclick' => 'js:checkAll(this.id)',
        'label' => 'Установить / Снять все списки',
        'labelOptions' => [
            'class' => 'checkbox-inline'
        ]
]);

$this->registerJs("
function checkAll(id){
	var selector = $('#' + id).is(':checked') ? ':not(:checked)' : ':checked';
    var inp = (id == 'set-clear') ? '#permissions input[name^=\"Group\"]' : 'input[value=\"index\"]';
    $(inp + selector).each(function() {jQuery(this).trigger('click');});
};", \yii\web\View::POS_END);
?>
</div>

<?php
foreach(Yii::$app->modules as $key => $module):

if (!is_object($module))
    $module = Yii::$app->getModule($key);

if (!empty($module->title) && $module->permissions): ?>

<div id="permissions" class="form-group">
    <label class="control-label col-sm-2">Модуль "<?=$module->title?>"</label>
    <div class="col-sm-10">
    
    <?php
	foreach($module->permissions as $k => $val)
    {
        echo '<b>'.$val['controller'].'</b>'; 
        unset($val['controller']);
        echo Html::checkboxList('Group[permissions]['.$key.']['.$k.']', $permissions[$key][$k], $val, ['item'=>
            function ($index, $label, $name, $checked, $value){
                return Html::checkbox($name, $checked, [
                    'value' => $value,
                    'label' => $label,
                    'labelOptions' => [
                        'class' => 'checkbox-inline'
                    ]
                ]);
            }]
        );
    }; ?>
    
    </div>
</div>

<?php 
endif;
endforeach;
?>