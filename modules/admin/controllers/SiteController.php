<?php
namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\controllers\AdminController;
use app\models\User;

/**
 * Site controller
 */
class SiteController extends AdminController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
