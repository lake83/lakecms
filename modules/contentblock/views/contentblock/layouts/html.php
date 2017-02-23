<?php
/**
 * Шаблон произвольного html блока
 */
?>
<?php if(!empty($model['css'])) $this->registerCss($model['css']);?>

<?= $model['text']?>

<?php if(!empty($model['js'])) $this->registerJs($model['js']);?>