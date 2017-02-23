<?php
/**
 * This is the template for generating a frontend controller class file.
 */
 
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\controller\Generator */

$namespace = $generator->getControllerNamespace();

$module = explode('\\', $namespace);

echo "<?php\n";
?>

namespace <?=$namespace?>;

/**
 * FrontendController implements the frontend actions for <?= ucfirst($module[2]) ?> module.
 */
class FrontendController extends \app\controllers\BaseFrontendController
{
    public $menu = [
<?php foreach ($generator->getActionIDs() as $action): ?>
        '<?= $module[2] ?>/frontend/<?= $action ?>' => '<?= ucfirst($action) ?>',
<?php endforeach; ?>
    ];
<?php foreach ($generator->getActionIDs() as $action): ?>
    
    public function action<?= Inflector::id2camel($action) ?>()
    {
        return $this->render('<?= $action ?>');
    }
<?php endforeach; ?>
}