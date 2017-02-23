<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\helpers\Html;

/**
 * BaseFrontendController implements all FrontendController.
 */
class BaseFrontendController extends \yii\web\Controller
{
    public $layout = false;
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['menu'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }
    
    public function actionMenu()
    {
        return Html::dropDownList('actions', '', $this->menu,
            ['class' => 'form-control', 'prompt' => '- выбрать -', 'onchange' => 'js:saveAction(this.value)']
        );
    }
}
?>