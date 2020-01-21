<?php

namespace backend\models\search;

use common\models\Categories;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PostAd;

/**
 * PostAdSearch represents the model behind the search form about `common\models\PostAd`.
 */
class PostAdSearch extends PostAd
{
    public $category;
    public $subcategory;

    public $user_name;
    public $user_email;
    public $user_mobile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'country', 'state', 'city', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['title', 'slug', 'description', 'negotiate', 'mobile', 'mobile_hidden', 'tags', 'address', 'termCondition', 'status', 'latitude', 'longitude'], 'safe'],
            [['price'], 'number'],
            [['category','subcategory','user_name','user_email','user_mobile'],'safe']
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
    public function search($params,$status= null)
    {
        if(!empty($status)){
            $query = PostAd::find()->where(['post_ads.status'=>$status]);
        }
        else{
            $query = PostAd::find();
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['user','userprofile'/*,'adscategory'*/]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'post_ads.user_id' => $this->user_id,
            'post_ads.price' => $this->price,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'post_ads.created_at' => $this->created_at,
            'post_ads.updated_at' => $this->updated_at,
            'post_ads.created_by' => $this->created_by,
            'post_ads.updated_by' => $this->updated_by,
            'post_ads.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'post_ads.title', $this->title])
            ->andFilterWhere(['like', 'post_ads.slug', $this->slug])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'negotiate', $this->negotiate])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'mobile_hidden', $this->mobile_hidden])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'termCondition', $this->termCondition])
            ->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'longitude', $this->longitude]);

        $query->andFilterWhere(['like','user.mobile', $this->user_mobile]);
        $query->andFilterWhere(['like','user.email', $this->user_email]);
        $query->andFilterWhere(['like','user_profile.name', $this->user_name]);

        $array_c=array(0);
        if(!empty($params['PostAdSearch']['category'])){
            $category=Categories::find()->where(['like','title',$this->category])
                ->andWhere(['parent_id'=>0])->all();
            foreach($category as $c){
                $array_c[]=$c->id;
            }
        }

        if(!empty($params['PostAdSearch']['subcategory'])){
            $category=Categories::find()->where(['like','title',$this->subcategory])
                ->andWhere(['not',['parent_id'=> 0]])->all();
            foreach($category as $c){
                $array_c[]=$c->id;
            }
        }
       /* if(!empty($params['PostAdSearch']['category']) || !empty($params['PostAdSearch']['subcategory'])){
            $query->andFilterWhere(['ads_category.cat_id'=> $array_c]);
        }*/

        return $dataProvider;
    }
}
