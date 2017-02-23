<?php
namespace app\modules\translations\models\query;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use app\modules\translations\components\ClassNameTrait;

class TranslateModelsQuery extends ActiveQuery
{
    use ClassNameTrait;
    
    /**
     * @var string the name of the lang field of the translation table. Default to 'lang'.
     */
    public $languageField = 'lang';

    /**
     * Scope for querying by languages
     * @param $language
     * @return ActiveQuery
     */
    public function localized($language = null)
    {
        if (is_array($langs = Yii::$app->params['languages']) && count($langs) > 1) {
            if (!$language)
                $language = Yii::$app->language;

            $this->with(['translation' => function ($query) use ($language) {
                $query->andWhere([$this->languageField => substr($language, 0, 2)]);
            }]);
        }
        return $this;
    }
    
    public function populate($rows)
    {
        $models = parent::populate($rows);

        if (!$this->asArray) {
            return $models;
        } else {
            if ($module = Yii::$app->getModule($this->getShortClassName($this->modelClass, true))->translations) {
                if ($columns = ArrayHelper::getColumn($module[$this->getShortClassName($this->modelClass)]['attributes'], 'id')) {
                    foreach ($models as &$model) {
                        if (!empty($model['translation'])) {
                            foreach ($columns as $column) {
                                if (array_key_exists($column, $model)) {
                                    foreach ($model['translation'] as $translation) {
                                        if ($translation['column'] == $column) {
                                            $value = $translation['value'];
                                            break;
                                        }  
                                    }
                                    if (isset($value) && !empty($value))
                                        $model[$column] = $value;
                                }
                            }
                        }
                    }
                    unset($model['translation']);
                }
            }
            return $models;
        }
    }
}