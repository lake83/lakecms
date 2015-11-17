<?php

namespace app\modules\admin;

class Module extends \yii\base\Module
{
    public $title = 'Админка';
    
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function init()
    {
        parent::init();
    }
}
