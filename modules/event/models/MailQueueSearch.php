<?php

namespace app\modules\event\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\event\models\MailQueue;

/**
 * MailQueueSearch represents the model behind the search form about `app\modules\event\models\MailQueue`.
 */
class MailQueueSearch extends MailQueue
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['created_at'], 'date', 'format' => 'd.m.Y'],
            [['subject', 'text', 'to'], 'safe'],
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
        $query = MailQueue::find();

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
            'FROM_UNIXTIME(created_at, "%d.%m.%Y")' => $this->created_at
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'to', $this->to]);

        return $dataProvider;
    }
}
