<?php 
namespace app\modules\admin\controllers\actions;

use Yii;
use yii\web\NotFoundHttpException;

class Delete extends \yii\base\Action
{
    public $model;
    
    public function run()
    {
        $model = $this->model;
        $model = $model::findOne(Yii::$app->request->getQueryParam('id'));
        
        if ($model == null) 
            throw new NotFoundHttpException(Yii::t('main', 'Страница не найдена.'));
        
        $model->delete();

        return $this->controller->redirect(['index']);
    }
} 
?>