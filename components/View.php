<?php
namespace app\components;

class View extends \yii\web\View
{
    /**
     * @var string устанавливает мета тег keywords если указан для страницы в структуре сайта
     * или переопределяется в подключенном на страницу блоке как $this->view->keywords.
     */
    public $keywords;
    
    /**
     * @var string устанавливает мета тег description если указан для страницы в структуре сайта
     * или переопределяется в подключенном на страницу блоке как $this->view->description.
     */
    public $description;
}
?>