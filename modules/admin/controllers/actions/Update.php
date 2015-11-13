<?php 
namespace app\modules\admin\controllers\actions;

use Yii;
use yii\web\NotFoundHttpException;

class Update extends \yii\base\Action
{
    public $model;
    
    public function run()
    {
        $model = $this->model;
        $id = Yii::$app->request->getQueryParam('id');
        $model = $model::findOne($id);
        
        if ($model == null) 
            throw new NotFoundHttpException(Yii::t('main', 'Страница не найдена.'));
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Изменения сохранены.');
            return $this->controller->redirect(['index']);
        } else {
            return $this->controller->render('update', [
                'model' => $model,
            ]);
        }
    }
} 
?>