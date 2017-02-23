<?php

namespace app\modules\menu\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\menu\models\MenuItems;

/**
 * MenuItemsSearch represents the model behind the search form about `app\modules\menu\models\MenuItems`.
 */
class MenuItemsSearch extends MenuItems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'parent_id', 'page_id', 'is_active'], 'integer'],
            [['title', 'link', 'options', 'before_link', 'after_link'], 'safe'],
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
        $query = MenuItems::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'parent_id' => $this->parent_id,
            'page_id' => $this->page_id,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'options', $this->options])
            ->andFilterWhere(['like', 'before_link', $this->before_link])
            ->andFilterWhere(['like', 'after_link', $this->after_link]);

        return $dataProvider;
    }
}
