<?php

namespace frontend\modules\gloomme\jobcategory\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\gloomme\jobcategory\models\Jobcategory;

/**
 * NewsSearch represents the model behind the search form about `frontend\modules\brsoftech\news\models\News`.
 */
class JobCategorySearch extends Jobcategory {

    public $status;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title','parentid'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = JobCategory::find();
        $query->orderBy('id DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'parentid' => $this->parentid,
        ]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        return $dataProvider;
    }

}
