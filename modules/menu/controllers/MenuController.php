<?php

namespace app\modules\menu\controllers;

use app\controllers\AdminController;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => $this->actionsPath.'Index',
                'search' => $this->modelPath.'MenuSearch'
            ],
            'create' => [
                'class' => $this->actionsPath.'Create',
                'model' => $this->modelPath.'Menu'
            ],
            'update' => [
                'class' => $this->actionsPath.'Update',
                'model' => $this->modelPath.'Menu'
            ],
            'delete' => [
                'class' => $this->actionsPath.'Delete',
                'model' => $this->modelPath.'Menu'
            ],
            'toggle' => [
                'class' => \pheme\grid\actions\ToggleAction::className(),
                'modelClass' => $this->modelPath.'Menu',
                'attribute' => 'is_active'
            ]
        ];
    }
}