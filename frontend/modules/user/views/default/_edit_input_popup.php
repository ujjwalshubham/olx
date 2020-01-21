<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$baseUrl=Yii::getAlias('@frontendUrl');
$sendOtp="'".$baseUrl."/user/send-otp'";

$js=" 
$(document).on('click','.edit-submit',function (e){
    var type=$('#type_check').val();
    var id=$('#edit-input-modal #user-id').val();
    if(type == 'email'){
        var identity=$('#edit-input-modal #user-email').val();
        if(identity=='') { 
            $('#edit-input-modal .field-user-email').addClass('has-error required');
            $('#edit-input-modal #user-email').attr('aria-invalid',true);
            $('#edit-input-modal #user-email').next('.help-block').addClass('error').text('Email cannot be blank.');
            return false;	
        }
    }
    if(identity!=''){        
        $.ajax({
            method: 'POST',
            url: $sendOtp,
            data: {id:id,identity:identity,type:type},
        })
        .done(function( resJson) { 
            try { 
                var obj = jQuery.parseJSON(resJson);
                if(obj.status && obj.error){
                    if(type == 'email'){
                        $('#edit-input-modal .field-user-email').addClass('has-error required');
                        $('#edit-input-modal #user-email').attr('aria-invalid',true);
                        $('#edit-input-modal #user-email').next('.help-block').addClass('error').text(obj.data['identity']);
                        return false;	
                    }
                }else{
                     $('#edit-input-modal').html('');
                     $('#edit-input-modal').html(resJson);   
                     return false;	
                }
            }
            catch(e) {
                $('#edit-input-modal').html('');
                $('#edit-input-modal').html(resJson);   
                return false;
            }
        }); 
    }else{            
        location.reload();
    }    
})";
$this->registerJs($js, \yii\web\VIEW::POS_END);
?>
<div class="modal-dialog emails_formcontent">
    <div class="modal-content">
        <div class="modal-header">
            <?php if($type == 'email') {?>
                <h4 class="modal-title" id="experiencesContact"><?php echo Yii::t('frontend', 'Email')?></h4>
            <?php } ?>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
            <?php $form = ActiveForm::begin(); ?>
                <?php echo $form->field($user, 'id')->hiddenInput()->label(false);?>
               <?php if($type == 'email') {?>
                <?php echo $form->field($user, 'email')->textInput(['type'=>'email','class'=>'input_field form-control register-mobile','placeholder'=>'Please Enter Email'])->label(false);?>
                <?php } ?>
            <input type="hidden" name="type" value="<?php echo $type?>" id="type_check"/>
            <a href="javascript:void(0)" class="login-sumbit edit-submit" id="submit_email               " name="signup-button"><?php echo Yii::t('frontend', 'Save')?></a>
            <?php ActiveForm::end(); ?>
        </div>
    </div><!-- /.modal-content -->
</div>
