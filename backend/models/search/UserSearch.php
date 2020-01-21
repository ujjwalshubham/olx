<?php

namespace backend\models\search;

use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    public $name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['created_at', 'updated_at', 'logged_at'], 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            [['created_at', 'updated_at', 'logged_at'], 'default', 'value' => null],
            [['username', 'auth_key', 'password_hash', 'email','mobile','name'], 'safe'],
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
     * @return ActiveDataProvider
     */
    public function search($params,$role=null)
    {
        if($role == User::ROLE_USER){
            $query = User::find()->where(['user_type'=>User::ROLE_USER]);
        }
        elseif($role == ''){
            $query = User::find()->where(['user_type'=>[User::ROLE_ADMINISTRATOR,User::ROLE_MANAGER]]);
        }
        else{
            $query = User::find()->where(['user_type'=>$role]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['userProfile']);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'user_profile.name', trim($this->name)]);

        if ($this->created_at !== null) {
            $query->andFilterWhere(['between', 'created_at', $this->created_at, $this->created_at + 3600 * 24]);
        }

        if ($this->updated_at !== null) {
            $query->andFilterWhere(['between', 'updated_at', $this->updated_at, $this->updated_at + 3600 * 24]);
        }

        if ($this->logged_at !== null) {
            $query->andFilterWhere(['between', 'logged_at', $this->logged_at, $this->logged_at + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'email', trim($this->email)])
            ->andFilterWhere(['like', 'mobile', trim($this->mobile)]);

        return $dataProvider;
    }
}
