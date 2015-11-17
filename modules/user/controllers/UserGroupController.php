<?php

namespace app\modules\user\controllers;

use app\modules\admin\controllers\AdminController;

/**
 * UserGroupController implements the CRUD actions for UserGroup model.
 */
class UserGroupController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'app\modules\admin\controllers\actions\Index',
                'search' => 'app\modules\user\models\UserGroupSearch'
            ],
            'create' => [
                'class' => 'app\modules\admin\controllers\actions\Create',
                'model' => 'app\modules\user\models\UserGroup',
                'success' => 'Група пользователей успешно создана.'
            ],
            'update' => [
                'class' => 'app\modules\admin\controllers\actions\Update',
                'model' => 'app\modules\user\models\UserGroup'
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\actions\Delete',
                'model' => 'app\modules\user\models\UserGroup'
            ]
        ];
    }
}
