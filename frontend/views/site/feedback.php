<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = 'Feedback';
$this->params['breadcrumbs'][] = $this->title;
$baseurl =\Yii::$app->getUrlManager()->getBaseUrl(); 
?>


<section id="main" class="category-page mid_container">
  <div class="container"> 
    <!-- breadcrumb -->
    <div class="breadcrumb-section">
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl;?>"><i class="fa fa-home"></i> <?php echo Yii::t('frontend', 'Home')?></a></li>
        <li><?php echo $this->title?> </li>
        <div class="ml-auto back-result"><a href=""><i class="fa fa-angle-double-left"></i> <?php echo Yii::t('frontend', 'Back to Results')?></a> </div>
      </ol>
      <h2 class="title"><?php echo $this->title?></h2>
    </div>
    <!-- breadcrumb -->
    
    <div class="row recommended-ads">
      <div class="col-sm-12">
		  
		  <?php if (Yii::$app->session->hasFlash('alert')):
			$alert = Yii::$app->session->getFlash('alert');
			?>
			<div class="alert <?php echo isset($alert['class']) ? $alert['class'] : ""; ?> alert-dismissable">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				<b><i class="icon fa fa-check"></i>Saved!</b>
				<?php echo isset($alert['body']) ? $alert['body'] : ""; ?>
			</div>
			<?php
		endif;
		?>
		  
		  
		  <?php $form = ActiveForm::begin(['id' => 'feedback-form']); ?>
        <div class="section feed-back ">
			<h4><strong><?php echo Yii::t('frontend', 'Tell us what you think of us')?></strong></h4><br>
			
			<h6><?php echo Yii::t('frontend', 'User Details')?></h6><br>
			<div class="row form-group">
				<div class="col-sm-6">
					<?php echo $form->field($model, 'name', ['inputOptions' => ['class' => 'form-control border-form','placeholder'=>Yii::t('frontend', 'Full Name')]])->label(false) ?>
					
				</div>
			</div>
			
			<div class="row form-group">
				<div class="col-sm-6">
					 <?php echo $form->field($model, 'email', ['inputOptions' => ['class' => 'form-control border-form','placeholder'=>Yii::t('frontend', 'Email')]])->label(false) ?>
					
				</div>
			</div>
			
			<div class="row form-group">
				<div class="col-sm-6">
					 <?php echo $form->field($model, 'phone', ['inputOptions' => ['class' => 'form-control border-form','placeholder'=>Yii::t('frontend', 'Phone Number')]])->label(false) ?>
					
				</div>
			</div>
			
			<div class="row form-group">
				<div class="col-sm-6">
					 <?php echo $form->field($model, 'subject', ['inputOptions' => ['class' => 'form-control border-form','placeholder'=>Yii::t('frontend', 'Subject')]])->label(false) ?>
				</div>
			</div>
			
			<div class="row form-group">
				<div class="col-sm-6">
					<h6><?php echo Yii::t('frontend', 'Is there anything you would like to tell us?')?></h6>
					 <?= $form->field($model, 'message',['template' => '{input}{error}','inputOptions' => ['class' => 'form-control border-form','placeholder'=>Yii::t('frontend', 'Message...')]])->textarea(['rows' => '4'])->label(false) ?>
				</div>
			</div>
			
			<div class="form-group">
				 <?php echo Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn bt_blue', 'name' => 'contact-button']) ?>
			</div>
			
          
                    
        </div>
        
          <?php ActiveForm::end(); ?>     
      </div>
    </div>
  </div>
</section>
