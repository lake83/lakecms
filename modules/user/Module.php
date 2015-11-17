<?php

namespace app\modules\user;

class Module extends \yii\base\Module
{
    use \app\widgets\ModuleTranslationsTrait;
    
    public $title = 'Пользователи';
    
    public $controllerNamespace = 'app\modules\user\controllers';

    public function init()
    {
        parent::init();
        $this->registerTranslations($this->id);
    }
}
