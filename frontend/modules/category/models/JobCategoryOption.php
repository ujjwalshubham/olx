<?php

namespace frontend\modules\gloomme\jobcategory\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

class JobCategoryOption extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%jobCategoryOption}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
          
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['jobCategoryId', 'deductCoin','description_en','description_zh'], 'required'],
            [['jobCategoryId','deductCoin'], 'integer'],
            [['created_at'], 'default', 'value' => time()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('common', 'ID'),
            'jobCategoryId' => Yii::t('backend', 'job Category Id'),
            'deductCoin' => Yii::t('backend', 'Coins'),
            'description_en' => Yii::t('backend', 'Description English'),
            'description_zh' => Yii::t('backend', 'Description Chinese'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }
    public function getParentTitle($id) {
        $data = Jobcategory::find()->where(['id' => $id])->one();
        return $data['title'];
    }

}
