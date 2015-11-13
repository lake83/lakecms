<?php 
namespace app\modules\admin\controllers\actions;

use Yii;

class Create extends \yii\base\Action
{
    public $model;
    public $success;
    
    public function run()
    {
        $model = new $this->model;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', $this->success);
            return $this->controller->redirect(['index']);
        } else {
            return $this->controller->render('create', [
                'model' => $model,
            ]);
        }
    }
} 
?>