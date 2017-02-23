<?php

namespace app\modules\menu\controllers;

use app\controllers\AdminController;

/**
 * MenuItemsController implements the CRUD actions for MenuItems model.
 */
class MenuItemsController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => $this->actionsPath.'Index',
                'search' => $this->modelPath.'MenuItemsSearch'
            ],
            'create' => [
                'class' => $this->actionsPath.'Create',
                'model' => $this->modelPath.'MenuItems'
            ],
            'update' => [
                'class' => $this->actionsPath.'Update',
                'model' => $this->modelPath.'MenuItems'
            ],
            'delete' => [
                'class' => $this->actionsPath.'Delete',
                'model' => $this->modelPath.'MenuItems'
            ],
            'toggle' => [
                'class' => \pheme\grid\actions\ToggleAction::className(),
                'modelClass' => $this->modelPath.'MenuItems',
                'attribute' => 'is_active'
            ]
        ];
    }
}