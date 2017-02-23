<?php 
namespace app\controllers\actions;

use Yii;

class Update extends \yii\base\Action
{
    use ActionsTraite;
    
    public $model;
    
    public function run()
    {
        return $this->actionBody($this->model, Yii::t('app', 'Изменения сохранены.'), 'update');
    }
} 
?>