<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ReportAdForm extends Model
{
    public $name;
    public $email;
    public $username;
    public $violation_type;
    public $username_other_person;
    public $url;
    public $violation_detail;
    

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['email', 'url','violation_detail'], 'required'],
            [['url'], 'url','defaultScheme' => 'http'],
            // We need to sanitize them
            [['name', 'email', 'violation_detail'], 'filter', 'filter' => 'strip_tags'],
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
            'username' => Yii::t('frontend', 'Username'),
            'violation_detail' => Yii::t('frontend', 'Violation detail'),
            
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

            Yii::$app->db->createCommand("insert into report_ad SET 
				name = '" . $postData['ReportAdForm']['name'] . "', " .
                " email ='" . $postData['ReportAdForm']['email'] . "', " .
                " username ='" . $postData['ReportAdForm']['username'] . "', " .
                " violation_type = '" . $postData['ReportAdForm']['violation_type'] . "', " .
                " username_other_person ='" . addslashes($postData['ReportAdForm']['username_other_person']) . "', " .
                " url ='" . addslashes($postData['ReportAdForm']['url']) . "', " .
                " violation_detail ='" . addslashes($postData['ReportAdForm']['violation_detail']) . "', " .
                " dtdate = '" . date('Y-m-d H:i:s') . "' ")->execute();
                
                return true;

        } else {
            return false;
        }
    }
}
