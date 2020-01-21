<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = 'Contact Us';
$this->params['breadcrumbs'][] = $this->title;
$baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
?>
<section id="main" class="category-page mid_container">
  <div class="container"> 
    <!-- breadcrumb -->
    <div class="breadcrumb-section">
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl;?>"><i class="fa fa-home"></i>  <?php echo Yii::t('frontend', 'Home')?></a></li>
        <li><?php echo $this->title ;?> </li>
        <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i> <?php echo Yii::t('frontend', 'Back to Results')?></a> </div>
      </ol>
      <h2 class="title"><?php echo $this->title ;?></h2>
    </div>
    <!-- breadcrumb -->
    
    <div class="row recommended-ads">
		
		<div class="col-sm-12">
			<div class="contact_map">
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3559.04117645305!2d75.77341231555711!3d26.8680239831468!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db5b390edee4d%3A0x179ebce856537028!2sBR%20Softech%20Pvt.%20Ltd!5e0!3m2!1sen!2sin!4v1569997836548!5m2!1sen!2sin" width="100%" height="350" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
			</div>
		</div>
		
      <div class="col-sm-8">
        <div class="section feed-back ">
			
			 <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
			<h4><strong><?php echo Yii::t('frontend', 'Contact Us')?></strong></h4><br>
			<div class="row form-group">
				<div class="col-sm-6">
					 <?php echo $form->field($model, 'name', ['inputOptions' => ['class' => 'form-control border-form','placeholder'=>Yii::t('frontend', 'Your Name')]])->label(false) ?>
				</div>
				<div class="col-sm-6">
					 <?php echo $form->field($model, 'email', ['inputOptions' => ['class' => 'form-control border-form','placeholder'=>Yii::t('frontend', 'Email')]])->label(false) ?>
				</div>
			</div>
			
			<div class="row form-group">
				<div class="col-sm-12">
					 <?php echo $form->field($model, 'subject', ['inputOptions' => ['class' => 'form-control border-form','placeholder'=>Yii::t('frontend', 'Subject')]])->label(false) ?>
				</div>
			</div>
			
			<div class="row form-group">
				<div class="col-sm-12">
					 <?= $form->field($model, 'body',['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form','placeholder'=>Yii::t('frontend', 'Message...')]])->textarea(['rows' => '4'])->label(false) ?>
				</div>
			</div>
			
			<div class="form-group">
				 <?php echo Html::submitButton(Yii::t('frontend', 'Send Message'), ['class' => 'btn bt_blue', 'name' => 'contact-button']) ?>
			</div>
			
          
             <?php ActiveForm::end(); ?>         
        </div>
      </div>
      
      
      
      
      
      
      
      
      
      
      
		
		<div class="col-sm-4">
        <div class="section feed-back ">
			<div class="contactUs-detail">
                        <h4 class="heading">Get In Touch</h4>

                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eget leo at velit</p>
                        <hr>
                        <h4 class="heading">Contact Information</h4>
                        <ul class="list-icons">
                            <li><i class="fa fa-map-marker"></i> <strong>Address:</strong> 142, Aksakcw land, Triswas</li>
                            <li><i class="fa fa-phone"></i> <strong>Phone:</strong> <a href="tel:1-972-8103-393">1-972-8103-393</a></li>
                            <li><i class="fa fa-envelope"></i> <strong>Email:</strong> <a href="">helpdesk.mshop@gmail.com</a>
                            </li>
                        </ul>
                    </div>
          
                    
        </div>
      </div>
    </div>
  </div>
</section>

