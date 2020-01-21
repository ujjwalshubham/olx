<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\models\CategoriesLang;
use yii\db\Query;
use yii\helpers\Url;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Categories extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public $image;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => true,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['title', 'status'], 'required'],
            [['description'], 'string'],
            [['title', 'slug'], 'string', 'max' => 200],
            [['created_at', 'updated_at', 'created_by', 'updated_by','image'],'safe'],
            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::statuses())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Returns user statuses list
     * @return array|mixed
     */
    public static function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('common', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_DELETED => Yii::t('common', 'Deleted')
        ];
    }
    
    
    
    public function getCategories_lang()
    {
        return $this->hasOne(CategoriesLang::className(), ['category_id' => 'id']);
    }
    
    
    public static function getAllCategory()
    {
       $query = new Query();
        $query->select(['*'])->from('categories')
					->andWhere(['status' => 1,'parent_id'=>0]);

        $command = $query->createCommand();
        return $command->queryAll();
    }
    
    public static function getCategoryNameById($cat_id)
    {
		
		$cookies = Yii::$app->request->cookies;
		
		if(isset($cookies['_locale']) && !empty($cookies['_locale'])){
		$lang = $cookies['_locale']->value;
		}else{
		$lang = 'en';	
		}
		
		
     /*  $cat_name = (new \yii\db\Query())
					->select(['title'])
					->from('categories')
					->where(['id' => $cat_id])
					->one();
					
		
		return  $cat_name;*/
		
	
		
		$lists= new Query();
        $lists = Categories::find();
        $lists->select(['categories.title', 
						  'categories_lang.id as  cat_lang_id',
						  'categories_lang.title as cat_lang_title']);  
        $lists->joinWith('categories_lang');
        $lists->andWhere(['categories.id' => $cat_id]);
        $lists->all();
        $command = $lists->createCommand();
        $cat_name = $command->queryOne();
		
		$ArrCatLang =  array();
		if($lang=='pt-BR'){
				if($cat_name['cat_lang_title']==''){
					$cat_name['title'] = 	$cat_name['title'];
				}else{
					$cat_name['title'] = 	$cat_name['cat_lang_title'];
				}
				
			}elseif($lang=='en'){
				$cat_name['title'] = 	$cat_name['title'];
			}
			$ArrCatLang= $cat_name;
		
		/*echo "<pre>";
		print_r($ArrCatLang);
		exit;*/
		
		return $ArrCatLang;
		
    }

    public function isParentCategory($cat_id){
        $category=Categories::find()->where(['id'=>$cat_id])->one();
        if(!empty($category)){
            if($category->parent_id == 0){
                return true;
            }
        }
        return false;
    }
}
