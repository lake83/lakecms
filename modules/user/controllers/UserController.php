<?php

namespace app\modules\user\controllers;

use app\modules\admin\controllers\AdminController;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'app\modules\admin\controllers\actions\Index',
                'search' => 'app\modules\user\models\UserSearch'
            ],
            'create' => [
                'class' => 'app\modules\admin\controllers\actions\Create',
                'model' => 'app\modules\user\models\User',
                'scenario' => 'createUser',
                'success' => 'Пользователь успешно создан.'
            ],
            'update' => [
                'class' => 'app\modules\admin\controllers\actions\Update',
                'model' => 'app\modules\user\models\User'
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\actions\Delete',
                'model' => 'app\modules\user\models\User'
            ]
        ];
    }
}
