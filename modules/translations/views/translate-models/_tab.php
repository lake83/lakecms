<?php
use app\components\RedactorTinymce;
    
foreach($translations['attributes'] as $field) {
    switch ($field['field']) {
        case 'textarea':
            echo isset($model) ? $form->field($model, $field['id'].'___'.$lang)->textArea(['rows' => '6'])->label($field['label'].' ('.$lang.')') :
                 $form->field($owner, $field['id'])->textArea(['rows' => '6']);
            break;
        case 'redactor':
            echo isset($model) ? $form->field($model, $field['id'].'___'.$lang)->widget(RedactorTinymce::className())->label($field['label'].' ('.$lang.')') :
                 $form->field($owner, $field['id'])->widget(RedactorTinymce::className());
            break;
        default:
            echo isset($model) ? $form->field($model, $field['id'].'___'.$lang)->label($field['label'].' ('.$lang.')') :
                 $form->field($owner, $field['id']);
    }
} ?>