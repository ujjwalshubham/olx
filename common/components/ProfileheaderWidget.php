<?php
namespace common\components;

use yii;
use yii\base\Widget;
use yii\helpers\Html;


class ProfileheaderWidget extends Widget{
    public $message;

    public function init(){
        parent::init();
		/* $rt = yii::$app->dbBlog->createCommand("SELECT * FROM wp_posts WHERE post_type = 'post' AND post_parent = 0 AND post_status = 'publish' order BY post_date desc LIMIT 5")->queryAll();
       //  print_r($rt);die;
        $this->message = '';
        if(count($rt)>0){
			foreach($rt as $tr){
					$this->message .= '<li>
              <div class="icon_box pull-left">
                <div class="top"> '.date('d',strtotime($tr['post_date'])).' </div>
                <div class="bott"> '.date('M',strtotime($tr['post_date'])).' </div>
              </div>
              <div class="right_text"> <a href="'.\Yii::$app->request->BaseUrl.'blog/'.$tr['post_name'].'">'.$tr['post_title'].'</a> </div>
            </li>';
			}
		}
        /* if($this->message===null){
            $this->message= 'Welcome User';
        }else{
            $this->message= 'Welcome '.$this->message;
        }*/
    }

    public function run(){  // @app/views/widget/_sidegetQuote
		//echo @app;die;
		//return $this->render('@app/themes/classified/widget/_category');
		return $this->render('@app/views/widget/_profileheader');
    }
}
?>
