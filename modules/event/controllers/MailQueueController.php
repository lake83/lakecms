<?php

namespace app\modules\event\controllers;

use Yii;
use app\controllers\AdminController;

/**
 * MailQueueController implements the CRUD actions for MailQueue model.
 */
class MailQueueController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => $this->actionsPath.'Index',
                'search' => $this->modelPath.'MailQueueSearch'
            ],
            'view' => [
                'class' => $this->actionsPath.'View',
                'model' => $this->modelPath.'MailQueue'
            ],
            'delete' => [
                'class' => $this->actionsPath.'Delete',
                'model' => $this->modelPath.'MailQueue'
            ]
        ];
    }
    
    /**
     * Удаление всех записей в таблице mail_queue
     */
    public function actionDeleteAll()
    {
        Yii::$app->db->createCommand()->truncateTable('mail_queue')->execute();
        
        return $this->redirect(['index']);
    }
}