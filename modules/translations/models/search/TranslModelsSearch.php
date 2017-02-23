<?php

namespace app\modules\translations\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\translations\models\TranslModels;

/**
 * TranslModelsSearch represents the model behind the search form about `app\modules\translations\models\TranslModels`.
 */
class TranslModelsSearch extends TranslModels
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module', 'model', 'column'], 'safe']
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
        $query = TranslModels::find();
        
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
            'id' => $this->id
        ]);

        $query->andFilterWhere(['like', 'module', $this->module])
              ->andFilterWhere(['like', 'model', $this->model])
              ->andFilterWhere(['like', 'column', $this->column]);

        return $dataProvider;
    }
}