<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\modules\user\models\SignupForm */

$this->title = Yii::t('frontend', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>


<section id="main" class="category-page mid_container">
		<div class="container">
			
		
			
			<div class="user_account">
			<div class="col-sm-8 col-md-6 offset-sm-2 offset-md-3">
                <div class="user-account">
                    <h2><?php echo Yii::t('frontend', 'Please Enter One Time Password')?></h2>

                 
                     <?php $register = ActiveForm::begin(['id' => 'form-signup', 'options' => ['role' => 'form','class'=>'login_con'], 'fieldConfig' => ['options' => ['tag' => 'span']]]); ?>
						
                        <div class="form-group">
                                <?php echo $register->field($model, 'mobile', ['template' => '{input}{error}', 'inputOptions' => ['class' => 'form-control','placeholder' => Yii::t('frontend', 'Mobile No'),  'maxlength'=>40]]) ?>
                        </div>

                        <button type="submit" name="submit" id="submit" class="btn bt_blue"><?php echo Yii::t('frontend', 'Register Now')?></button>
                   <?php ActiveForm::end(); ?>
                 
                    
                </div>
               </div>
			
			</div>
			
		</div>
	</section>
	

