<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\modules\user\models\ResetPasswordForm */

$this->title = Yii::t('frontend', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="site-reset-password">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?php echo $form->field($model, 'password')->passwordInput() ?>
                <div class="form-group">
                    <?php echo Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>-->




<section id="main" class="category-page mid_container">
		<div class="container">
			<div class="user_account">
			<div class="col-sm-8 col-md-6 offset-sm-2 offset-md-3">
                <div class="user-account">
                    <h2><?php echo Html::encode($this->title) ?></h2>
                    <!-- form -->
					<?php $form = ActiveForm::begin(['id' => 'reset-password-form', 'options' => ['class' => 'login_con']]); ?>
                    
                        <div class="form-group">
                            <?php echo $form->field($model, 'password', ['template' => '{input}{error}', 'inputOptions' => ['class' => 'form-control','placeholder'=>'Password']])->passwordInput() ?>
                        </div>
                        
                         <div class="form-group">
                            <?php echo $form->field($model, 'cpassword', ['template' => '{input}{error}', 'inputOptions' => ['class' => 'form-control','placeholder'=>'Confirm Password']])->passwordInput() ?>
                        </div>
                        
                         <?php echo Html::submitButton('Save', ['class' => 'btn bt_blue']) ?>
                    <?php ActiveForm::end(); ?>
                    <!-- form -->
              
			</div>
			
		</div>
	</section>
