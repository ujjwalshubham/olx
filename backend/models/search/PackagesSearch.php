<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Packages;

/**
 * PackagesSearch represents the model behind the search form of `common\models\Packages`.
 */
class PackagesSearch extends Packages
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ad_limit', 'ad_duration', 'featured_duration', 'urgent_duration', 'highlight_duration', 'group_removable', 'top_search_result', 'show_on_home', 'show_in_home_search', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'safe'],
            [['featured_project_fee', 'urgent_project_fee', 'highlight_project_fee'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Packages::find();

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
            'ad_limit' => $this->ad_limit,
            'ad_duration' => $this->ad_duration,
            'featured_project_fee' => $this->featured_project_fee,
            'featured_duration' => $this->featured_duration,
            'urgent_project_fee' => $this->urgent_project_fee,
            'urgent_duration' => $this->urgent_duration,
            'highlight_project_fee' => $this->highlight_project_fee,
            'highlight_duration' => $this->highlight_duration,
            'group_removable' => $this->group_removable,
            'top_search_result' => $this->top_search_result,
            'show_on_home' => $this->show_on_home,
            'show_in_home_search' => $this->show_in_home_search,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
