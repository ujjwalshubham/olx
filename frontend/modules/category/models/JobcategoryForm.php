<?php

namespace frontend\modules\gloomme\jobcategory\models;

use Yii;
use frontend\modules\gloomme\jobcategory\models\Jobcategory;
/**
 * ContactForm is the model behind the contact form.
 */
class JobcategoryForm extends Jobcategory
{
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [[ 'title'], 'required'],
            [[ 'title'], 'unique'],
            [['parentid'], 'default','value'=>0],
            [['created_at','updated_at'], 'default', 'value' => time()],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'title_en' => Yii::t('frontend', 'Title'),
           'parentid' => Yii::t('frontend', 'Parent Category'),
        ];
    }
}
