<?php
use common\components\SidenavWidget;
use common\components\ProfileheaderWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\file\FileInput;
?>
<style>
    .checkbox-inline, .checkbox-inline+.checkbox-inline, .radio-inline, .radio-inline+.radio-inline {
        margin-left: 0!important;
        margin-right: 10px!important;
        display: inline-block;
    .checkbox-inline, .radio-inline {
        position: relative;
        display: inline-block;
        padding-left: 20px;
        margin-bottom: 0;
        font-weight: 400;
        vertical-align: middle;
        cursor: pointer;
    }
</style>
<?php
//echo "<pre>"; print_r($custom_fields);exit;
?>
<?php if(isset($custom_fields) && !empty($custom_fields)){?>
    <?php foreach($custom_fields as $key=>$custom_field){?>
        <?php if($custom_field['custom_fields_type']['type']=='text'){?>

            <?= $form->field($model, 'custom_field['.$custom_field['custom_fields']['id'].']')
                ->textInput(['class'=>'form-control custom_field'])->label($custom_field['custom_fields']['label']); ?>



            <div class="row form-group">
                <div class="col-sm-12">
                    <label class="control-label"><?php echo $custom_field['custom_fields']['label']?>
                        <?php if($custom_field['custom_fields']['isRequired']== 'true'){?>
                            <span class="required custom_required">*</span>
                        <?php } ?>
                    </label>
                    <input type="text" class="form-control custom_field" name="custom_field[<?php echo$custom_field['custom_fields']['id']?>]" required>
                </div>
            </div>
        <?php } ?>
        <?php if($custom_field['custom_fields_type']['type']=='textarea'){?>
            <div class="row form-group">
                <div class="col-sm-12">
                    <label class="control-label"><?php echo $custom_field['custom_fields']['label']?>
                        <?php if($custom_field['custom_fields']['isRequired']== 'true'){?>
                            <span class="required custom_required">*</span>
                        <?php } ?>
                    </label>
                    <textarea class="form-control border-form custom_field" rows="7" name="custom_field[<?php echo$custom_field['custom_fields']['id']?>]"></textarea>
                </div>
            </div>
        <?php } ?>
        <?php if($custom_field['custom_fields_type']['type']=='checkbox'){?>
            <div class="row form-group">
                <div class="col-sm-12">
                    <label class="control-label"><?php echo $custom_field['custom_fields']['label']?>
                        <?php if($custom_field['custom_fields']['isRequired']== 'true'){?>
                            <span class="required custom_required">*</span>
                        <?php } ?>
                    </label><br>
                    <?php foreach($custom_field['custom_fields_options'] as $key1=>$option){?>
                        <div class="checkbox checkbox-inline checkbox-primary">
                            <input type="checkbox" class="custom_field" id="<?php echo $option['id']?>" name="custom_field[<?php echo$custom_field['custom_fields']['id']?>][<?php echo $option['id']?>]" value="<?php echo $option['label']?>">
                            <label for="<?php echo $option['id']?>"><?php echo $option['label']?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php if($custom_field['custom_fields_type']['type']=='radio'){?>
            <div class="row form-group">
                <div class="col-sm-12">
                    <label class="control-label"><?php echo $custom_field['custom_fields']['label']?>
                        <?php if($custom_field['custom_fields']['isRequired']== 'true'){
                            ?>
                            <span class="required custom_required">*</span>
                        <?php } ?>
                    </label><br>
                    <?php foreach($custom_field['custom_fields_options'] as $key1=>$option){?>
                        <div class="checkbox checkbox-inline checkbox-primary">
                            <input type="radio" class="custom_field" id="<?php echo $option['id']?>" name="custom_field[<?php echo$custom_field['custom_fields']['id']?>]" value="<?php echo $option['label']?>">
                            <label for="<?php echo $option['id']?>"><?php echo $option['label']?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>


    <?php } ?>
<?php } ?>
