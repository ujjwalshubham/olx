<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // We need to sanitize them
            [['name', 'subject', 'body'], 'filter', 'filter' => 'strip_tags'],
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
            'subject' => Yii::t('frontend', 'Subject'),
            'body' => Yii::t('frontend', 'Message'),
            
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email,$postData)
    {
        if ($this->validate()) {

            Yii::$app->db->createCommand("insert into contactus SET 
				name = '" . $postData['ContactForm']['name'] . "', " .
                " email ='" . $postData['ContactForm']['email'] . "', " .
                " subject = '" . $postData['ContactForm']['subject'] . "', " .
                " body ='" . addslashes($postData['ContactForm']['body']) . "', " .
                " dtdate = '" . date('Y-m-d H:i:s') . "' ")->execute();

            return Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom(Yii::$app->params['robotEmail'])
                ->setReplyTo([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();
                return true;
        } else {
            return false;
        }
    }
}
