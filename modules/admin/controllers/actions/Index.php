<?php 
namespace app\modules\admin\controllers\actions;

use Yii;

class Index extends \yii\base\Action
{
    public $search;
    
    public function run()
    {
        $searchModel = new $this->search;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
} 
?>