<?php 
namespace app\controllers\actions;

use Yii;

class Create extends \yii\base\Action
{
    use ActionsTraite;
    
    public $model;
    public $scenario;
    
    public function run()
    {
        $model = empty($this->scenario) ? new $this->model : new $this->model(['scenario' => $this->scenario]);
                
        return $this->actionBody($model, Yii::t('app', 'Запись добавлена.'), 'create');
    }
} 
?>