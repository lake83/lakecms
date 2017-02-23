<?php

namespace app\modules\contentblock\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\contentblock\models\Contentblock;

/**
 * ContentblockSearch represents the model behind the search form about `app\modules\context\models\Contentblock`.
 */
class ContentblockSearch extends Contentblock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'date', 'format' => 'd.m.Y'],
            [['title', 'text', 'js', 'css'], 'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Contentblock::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_active' => $this->is_active,
            'FROM_UNIXTIME(created_at, "%d.%m.%Y")' => $this->created_at,
            'FROM_UNIXTIME(updated_at, "%d.%m.%Y")' => $this->updated_at
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
              ->andFilterWhere(['like', 'text', $this->text])
              ->andFilterWhere(['like', 'js', $this->js])
              ->andFilterWhere(['like', 'css', $this->css]);

        return $dataProvider;
    }
}