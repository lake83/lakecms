<?php

namespace app\modules\translations\controllers;

use Yii;
use yii\base\Model;
use app\modules\translations\models\SourceMessage;
use yii\console\Application;
use yii\console\controllers\MessageController;
use yii\web\NotFoundHttpException;
use app\controllers\AdminController;

/**
 * TranslationsController implements the CRUD actions for SourceMessage model.
 */
class TranslationsController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => $this->actionsPath.'Index',
                'search' => $this->modelPath.'search\SourceMessageSearch'
            ],
            'delete' => [
                'class' => $this->actionsPath.'Delete',
                'model' => $this->modelPath.'SourceMessage'
            ]
        ];
    }
    
    /**
     * Сканирование файлов проекта на наличие не переведенных сообщений и добавление найденного в БД
     */
    public function actionScan()
    {
        $oldApp = Yii::$app;

        new Application(require(Yii::getAlias('@app/config/console.php')));
        Yii::$app->runAction('message/extract', ['@app/modules/translations/config/messages.php', 'interactive' => false]);

        Yii::$app = $oldApp;
                
        Yii::$app->session->setFlash('success', 'Сканирование завершено.');
        return $this->redirect(['index']);
    }
    
    /**
     * @param integer $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        /** @var SourceMessage $model */
        $model = $this->findModel($id);
        $model->initMessages();

        if (Model::loadMultiple($model->messages, Yii::$app->request->post()) && Model::validateMultiple($model->messages)) {
            $model->saveMessages();
            Yii::$app->session->setFlash('success', 'Изменения сохранены.');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * @param array|integer $id
     * @return SourceMessage|SourceMessage[]
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $query = SourceMessage::find()->where('id = :id', [':id' => $id]);
        $models = is_array($id)
            ? $query->all()
            : $query->one();
        if (!empty($models)) {
            return $models;
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }
}
