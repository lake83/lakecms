<?php
namespace app\modules\translations\components;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use app\modules\translations\models\query\TranslateModelsQuery;
use app\modules\translations\models\TranslateModels;

class i18nActiveRecord extends ActiveRecord
{
    use ClassNameTrait;
    
    public static function find()
    {
        return new TranslateModelsQuery(get_called_class());
    }
    
    /**
     * Relation для модели
     * Использование:
     * - с текущим языком
     * Contentblock::find()->where(['id' => 3])->localized()->asArray()->one();
     * - с заданым языком
     * Contentblock::find()->where(['id' => 3])->localized('en')->asArray()->one();
     * @return ActiveQuery
     */
    public function getTranslation()
    {
        $className = get_class($this);
        return $this->hasMany(TranslateModels::className(), ['owner_id' => 'id'])
               ->andWhere(['model' => $this->getShortClassName($className), 'module' => $this->getShortClassName($className, true)])->asArray();
    }
    
    /**
     * Handle 'afterFind' event of the owner.
     */
    public function afterFind()
    {
        $className = get_class($this);
        
        if ($module = Yii::$app->getModule($this->getShortClassName($className, true))->translations) {
            if ($columns = ArrayHelper::getColumn($module[$this->getShortClassName($className)]['attributes'], 'id')) {
                if ($this->isRelationPopulated('translation') && $related = $this->getRelatedRecords()['translation']) {
                    foreach ($columns as $column) {
                        if ($this->hasAttribute($column)) {
                            foreach ($related as $translation) {
                                if ($translation['column'] == $column) {
                                    $value = $translation['value'];
                                    break;
                                }
                            }
                            if (isset($value) && !empty($value))
                                $this->setAttribute($column, $value);
                        }
                    }
                }
            }
        }
        parent::afterFind();
    }     
}