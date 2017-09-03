<?php
namespace app\config;

use Yii;
use yii\base\BootstrapInterface;
use app\components\CmsHelper;

class settings implements BootstrapInterface 
{
    private $db;

    public function __construct()
    {
        $this->db = Yii::$app->db;
    }

    /**
    * Bootstrap method to be called during application bootstrap stage.
    * Loads all the settings into the Yii::$app->params array
    * @param Application $app the application currently running
    */
    public function bootstrap($app)
    {
        if (!$settings = $app->cache->get('settings')) {
            foreach ($this->db->createCommand("SELECT name, value FROM settings")->queryAll() as $val) {
                if (strpos($val['value'], '=>'))
                    $val['value'] = CmsHelper::settings_array($val['value']);
                $settings[$val['name']] = $val['value'];
            }
            $app->cache->set('settings', $settings, 0, new \yii\caching\TagDependency(['tags' => 'settings']));           
        }
        $app->params = $settings;
        
        if (!$app instanceof \yii\console\Application) {
            // Установка язиков для urlManager
            if (is_array($langs = $app->params['languages']) && count($langs) > 1) {
                $app->urlManager->languages = array_keys($langs);
            }
            // Установка темы в админке
            Yii::$container->set('dmstr\web\AdminLteAsset', ['skin' => $app->params['skin']]);
        }
    }
}
?>