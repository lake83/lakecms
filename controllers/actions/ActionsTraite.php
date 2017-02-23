<?php
namespace app\controllers\actions;

use Yii;
use yii\web\NotFoundHttpException;

trait ActionsTraite
{
    /**
     * Аякс валидация, сохранение и вывод для экшенов Create и Update
     * @param string $model модель
     * @param string $message сообщение при успешном сохранении
     * @param string $view вид  
     * @return mixed
     */
    protected function actionBody($model, $message, $view)
    {
        $model = is_object($model) ? $model : $this->findModel($model);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', $message);
            return $this->controller->redirect(['index']);
        } else {
            return Yii::$app->request->isAjax ? $this->controller->renderAjax($view, [
                'model' => $model
            ]) : $this->controller->render($view, [
                'model' => $model
            ]);
        }
    }
    
    /**
     * Находит модель по первичному ключу.
     * Если модель не найдена, дает исключение 404.
     * @param object $model экземпляр модели
     * @return загруженая модель
     * @throws NotFoundHttpException если модель не найдена
     */
    protected function findModel($model)
    {
        if (($model = $model::findOne(Yii::$app->request->getQueryParam('id'))) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Страница не найдена.'));
        }
    }
}
?>