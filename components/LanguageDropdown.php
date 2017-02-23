<?php
namespace app\components;

use Yii;
use yii\bootstrap\Dropdown;

class LanguageDropdown extends Dropdown
{
    private static $_labels;

    private $_isError;

    public function init()
    {
        $route = Yii::$app->controller->route;
        $appLanguage = Yii::$app->language;
        $this->_isError = $route === Yii::$app->errorHandler->errorAction;

        foreach (Yii::$app->urlManager->languages as $language) {
            $isWildcard = substr($language, -2)==='-*';
            if (
                $language===$appLanguage ||
                // Also check for wildcard language
                $isWildcard && substr($appLanguage,0,2)===substr($language,0,2)
            ) {
                continue;   // Exclude the current language
            }
            if ($isWildcard) {
                $language = substr($language,0,2);
            }
            $this->items[] = [
                'label' => self::label($language),
                'url' => substr_replace(Yii::$app->request->url, $language, 1, strlen($appLanguage))
            ];
        }
        parent::init();
    }

    public function run()
    {
        // Only show this widget if we're not on the error page
        if ($this->_isError) {
            return '';
        } else {
            return parent::run();
        }
    }

    public static function label($code)
    {
        if (self::$_labels===null) {
            self::$_labels = Yii::$app->params['languages'];
        }
        return isset(self::$_labels[$code]) ? self::$_labels[$code] : null;
    }
}