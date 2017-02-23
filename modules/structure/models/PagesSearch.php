<?php

namespace app\modules\structure\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\structure\models\Pages;

/**
 * PagesSearch represents the model behind the search form about `app\modules\structure\models\Pages`.
 */
class PagesSearch extends Pages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'seo_key'], 'string', 'max' => 255],
            [['title', 'seo_key', 'seo_description'], 'safe']
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Pages::find();
        
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
            'is_active' => $this->is_active
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
              ->andFilterWhere(['like', 'seo_key', $this->seo_key])
              ->andFilterWhere(['like', 'seo_description', $this->seo_description]);

        return $dataProvider;
    }
}