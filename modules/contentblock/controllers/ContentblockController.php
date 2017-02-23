<?php

namespace app\modules\contentblock\controllers;

use app\controllers\AdminController;

/**
 * ContentblockController implements the CRUD actions for Contentblock model.
 */
class ContentblockController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => $this->actionsPath.'Index',
                'search' => $this->modelPath.'ContentblockSearch'
            ],
            'create' => [
                'class' => $this->actionsPath.'Create',
                'model' => $this->modelPath.'Contentblock'
            ],
            'update' => [
                'class' => $this->actionsPath.'Update',
                'model' => $this->modelPath.'Contentblock'
            ],
            'delete' => [
                'class' => $this->actionsPath.'Delete',
                'model' => $this->modelPath.'Contentblock'
            ],
            'toggle' => [
                'class' => \pheme\grid\actions\ToggleAction::className(),
                'modelClass' => $this->modelPath.'Contentblock',
                'attribute' => 'is_active'
            ]
        ];
    }
}