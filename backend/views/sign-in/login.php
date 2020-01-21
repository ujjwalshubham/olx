<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */

$this->title = Yii::t('backend', 'Sign In');
$this->params['breadcrumbs'][] = $this->title;
$this->params['body-class'] = 'login-page';
?>

<div class="page-content">
    <div class="container">
        <div class="row">
            <!-- Login card -->
            <div class="col-md-4 col-md-offset-4">
                <div class="text-center">
                    <?php $logo=\common\components\AppHelper::getSiteLogo();?>
                    <img class="img-responsive" src="<?php echo $logo; ?>"/>
                </div>
                <div class="card" id="admin_login_card">
                    <h3 class="card-header h4">Login</h3>
                    <div class="card-block">
                        <span style="color:#df6c6e;"></span>
                        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                            <div class="body">
                                <?php echo $form->field($model, 'username')->textInput(['placeholder'=>'Username'])->label(false); ?>
                                <?php echo $form->field($model, 'password')->passwordInput(['placeholder'=>'Password'])->label(false); ?>
                                <?php //echo $form->field($model, 'rememberMe')->checkbox(['class'=>'simple']) ?>
                                <div class="form-group">
                                    <label for="frontend_login_remember" class="css-input switch switch-sm switch-app">
                                        <input type="checkbox" id="frontend_login_remember" /><span></span> Remember me</a>
                                    </label>
                                </div>
                            </div>
                            <div class="footer">
                                <?php echo Html::submitButton(Yii::t('backend', 'Login'), [
                                    'class' => 'btn btn-primary btn-flat btn-block',
                                    'name' => 'login-button'
                                ]) ?>
                            </div>
                        <?php ActiveForm::end() ?>
                    </div>
                    <!-- .card-block -->
                </div>
                <!-- .card -->
            </div>
            <!-- .col-md-6 -->
            <!-- End login -->
            <!-- End sign up -->
        </div>
        <!-- .row -->
    </div>
    <!-- .container -->
</div>