<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="modal-dialog emails_formcontent">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="experiencesContact"><?php echo Yii::t('frontend', 'Verify Otp')?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
            <?php
                $form = ActiveForm::begin(
                    ['id'=>'otp-form',
                        'enableAjaxValidation' => false,
                        'method'=>"post",'class'=>'form-horizontal']);
                echo $form->field($model, 'user_id')->hiddenInput()->label(false);
                if($type == 'email'){
                    $label='Enter the 4-digit code send via SMS on '.$user->mobile;
                }
              
                echo $form->field($model, 'otp')->textInput(['placeholder'=>'Please enter otp'])->label($label);

                ?>
                <input type="hidden" name="type" value="<?php echo $type?>" id="type_check"/>
                <?php echo Html::submitButton(Yii::t('frontend', 'Verify'),
                    ['id'=>"otp-submit",'class' => 'login-sumbit edit-submit', 'name' => 'login-button']);
                ActiveForm::end(); ?>
        </div>
    </div><!-- /.modal-content -->
</div>
