<?php 
namespace app\controllers\actions;

use Yii;

class Delete extends \yii\base\Action
{
    use ActionsTraite;
    
    public $model;
    
    public function run()
    {
        $this->findModel($this->model)->delete();

        return $this->controller->redirect(['index']);
    }
} 
?>