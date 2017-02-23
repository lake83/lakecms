<?php

namespace app\modules\event\controllers;

use app\controllers\AdminController;

/**
 * MailTemplatesController implements the CRUD actions for MailTemplates model.
 */
class MailTemplatesController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => $this->actionsPath.'Index',
                'search' => $this->modelPath.'MailTemplatesSearch'
            ],
            'create' => [
                'class' => $this->actionsPath.'Create',
                'model' => $this->modelPath.'MailTemplates'
            ],
            'update' => [
                'class' => $this->actionsPath.'Update',
                'model' => $this->modelPath.'MailTemplates'
            ],
            'delete' => [
                'class' => $this->actionsPath.'Delete',
                'model' => $this->modelPath.'MailTemplates'
            ],
            'toggle' => [
                'class' => \pheme\grid\actions\ToggleAction::className(),
                'modelClass' => $this->modelPath.'MailTemplates',
                'attribute' => 'is_active'
            ]
        ];
    }
}