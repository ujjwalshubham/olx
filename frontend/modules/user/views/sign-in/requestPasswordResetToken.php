<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\modules\user\models\PasswordResetRequestForm */

$this->title =  Yii::t('frontend', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="site-request-password-reset">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                <?php echo $form->field($model, 'email') ?>
                <div class="form-group">
                    <?php echo Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
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
					<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'options' => ['class' => 'login_con']]); ?>
                    
                        <div class="form-group">
                            <?php echo $form->field($model, 'email', ['template' => '{input}{error}', 'inputOptions' => ['class' => 'form-control','placeholder'=>Yii::t('frontend', 'E-mail')]]) ?>
                            
                        </div>
                         <?php echo Html::submitButton('Submit', ['class' => 'btn bt_blue']) ?>
                    <?php ActiveForm::end(); ?>
                    <!-- form -->
              
			</div>
			
		</div>
	</section>
	
