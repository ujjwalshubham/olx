<?php

namespace frontend\modules\category\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use trntv\filekit\behaviors\UploadBehavior;
use frontend\modules\category\models\category;

class category extends ActiveRecord {

    public $image;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%categories}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'immutable' => false,
                'ensureUnique' => true,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'slug', 'shortdecription'], 'required'],
            [['slug'], 'unique'],
            [['parentid'], 'integer'],
            [['image'], 'safe'],
            [['base_url', 'fullimage', 'metatitle', 'metadescription'], 'safe'],
            [['created_at'], 'default', 'value' => time()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Title'),
            'shortdecription' => Yii::t('backend', 'Short Decription'),
            'slug' => Yii::t('backend', 'Slug'),
            'metatitle' => Yii::t('backend', 'Meta Title'),
            'metadescription' => Yii::t('backend', 'Meta Description'),
            'parentid' => Yii::t('backend', 'Parent category'),
            'image' => Yii::t('backend', 'Image'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }

    public function getParentTitle($id) {
        $data = categories::find()->where(['id' => $id])->one();
        return $data['title'];
    }

    public static function getParentCat() {
        return self::find()->where(['parentid' => 0])->all();
    }

    public static function getSubCat($parentId) {
        return self::find()->where(['parentid' => $parentId])->all();
    }

    public function getCategoryName($categoryId) {
        $skillsId = explode(',', $categoryId);
        $getSkills = array();
        foreach ($skillsId as $skillsData) {
            $getSkills[] = Jobcategory::find()->where(['id' => $skillsData])->one();
        }
        $myArray = array();
        foreach ($getSkills as $dataSkills) {
            $myArray[] = $dataSkills['title'];
        }
        return implode(', ', $myArray);
    }

}
