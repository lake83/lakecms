<?php
/**
 * This is the template for generating a controller class file.
 */

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\controller\Generator */

$class = StringHelper::basename($generator->controllerClass);
$model = ucfirst($generator->controllerID);

echo "<?php\n";
?>

namespace <?= isset($namespace) ? $namespace : $generator->getControllerNamespace() ?>;

use app\controllers\AdminController;

/**
 * <?=$class?> implements the CRUD actions for <?=$model?> model.
 */
class <?=$class?> extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => $this->actionsPath.'Index',
                'search' => $this->modelPath.'<?=$model?>Search'
            ],
            'create' => [
                'class' => $this->actionsPath.'Create',
                'model' => $this->modelPath.'<?=$model?>'
            ],
            'update' => [
                'class' => $this->actionsPath.'Update',
                'model' => $this->modelPath.'<?=$model?>'
            ],
            'delete' => [
                'class' => $this->actionsPath.'Delete',
                'model' => $this->modelPath.'<?=$model?>'
            ],
            'toggle' => [
                'class' => \pheme\grid\actions\ToggleAction::className(),
                'modelClass' => $this->modelPath.'<?=$model?>',
                'attribute' => 'is_active'
            ]
        ];
    }
}