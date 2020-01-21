<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FeedbackForm extends Model
{
    public $name;
    public $email;
    public $phone;
    public $subject;
    public $message;
    

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'phone','subject', 'message'], 'required'],
            // We need to sanitize them
            [['name', 'subject', 'message','email','phone'], 'filter', 'filter' => 'strip_tags'],
            // email has to be a valid email address
            ['email', 'email']
            // verifyCode needs to be entered correctly
        

        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('frontend', 'Name'),
            'email' => Yii::t('frontend', 'Email'),
            'phone' => Yii::t('frontend', 'Phone'),
            'subject' => Yii::t('frontend', 'Subject'),
            'message' => Yii::t('frontend', 'Message'),
            
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string $email the target email address
     * @return boolean whether the model passes validation
     */
    public function feedback($postData)
    {
		
        if ($this->validate()) {

            Yii::$app->db->createCommand("insert into feedback SET 
				name = '" . $postData['FeedbackForm']['name'] . "', " .
                " email ='" . $postData['FeedbackForm']['email'] . "', " .
                " phone ='" . $postData['FeedbackForm']['phone'] . "', " .
                " subject = '" . $postData['FeedbackForm']['subject'] . "', " .
                " message ='" . addslashes($postData['FeedbackForm']['message']) . "', " .
                " dtdate = '" . date('Y-m-d H:i:s') . "' ")->execute();
                
                return true;

        } else {
            return false;
        }
    }
}
