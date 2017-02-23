<?php

namespace app\modules\translations\models\query;

use Yii;
use yii\db\ActiveQuery;
use app\modules\translations\models\Message;

class SourceMessageQuery extends ActiveQuery
{
    public function notTranslated()
    {
        $this->andWhere(['not in', 'id', $this->getIds()]);
        return $this;
    }

    public function translated()
    {
        $this->andWhere(['in', 'id', $this->getIds()]);
        return $this;
    }
    
    protected function getIds()
    {
        $messageTableName = Message::tableName();
        $query = Message::find()->select($messageTableName . '.id');
        $languages = array_keys(Yii::$app->params['languages']);
        $i = 0;
        if (!empty($languages) && is_array($languages) && count($languages) > 1) {
            foreach ($languages as $language) {
                if ($i === 0) {
                    $query->andWhere($messageTableName . '.language = :language and ' . $messageTableName . '.translation is not null', [':language' => $language]);
                } else {
                    $query->innerJoin($messageTableName . ' t' . $i, 't' . $i . '.id = ' . $messageTableName . '.id and t' . $i . '.language = :language and t' . $i . '.translation is not null', [':language' => $language]);
                }
                $i++;
            }
        }
        $ids = $query->indexBy('id')->all();
        return array_keys($ids);
    }
}
