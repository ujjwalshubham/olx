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
<?php //echo "<pre>"; print_r($custom_fields);exit;?>
<?php if(isset($custom_fields) && !empty($custom_fields)){
         foreach($custom_fields as $key=>$custom_field){
             if($custom_field['custom_fields']['isRequired']== 'true'){
                $class='form-control custom_field requiredVal';
                 $class_c='requiredVal';
                 $label_val=$custom_field['custom_fields']['label'];
                $label=$custom_field['custom_fields']['label'].'<span class="required custom_required">*</span>';
            } else{
                 $class_c='';
                 $label_val=$custom_field['custom_fields']['label'];
                $class='form-control custom_field';
                $label=$custom_field['custom_fields']['label'];
            }

            if($custom_field['custom_fields_type']['type']=='text'){
                echo $form->field($model, 'custom_field['.$custom_field['custom_fields']['id'].']')
                    ->textInput(['class'=>$class,'data-label'=>$label_val])->label($label);
             }

             if($custom_field['custom_fields_type']['type']=='textarea'){
                 echo $form->field($model, 'custom_field['.$custom_field['custom_fields']['id'].']')
                     ->textarea(['class'=>$class,'data-label'=>$label_val])->label($label);
             }

             if($custom_field['custom_fields_type']['type'] == 'radio'){ ?>
                     <?php $idfield=$custom_field['custom_fields']['id']; ?>
                     <div class="radioitemlist field-postad-custom_field-<?php echo $idfield?>">
                         <label class="control-label"><?= $label; ?>:</label>
                         <input type="hidden" name="PostAd[custom_field][<?php echo $idfield; ?>]" value="">
                         <div data-label="<?php echo $label_val ?>" class="<?php echo $class_c ?>" id="postad-custom_field-<?php echo $idfield?>">
                             <?php
                             $i=0;
                             foreach($custom_field['custom_fields_options'] as $key1=>$option){ ?>
                                 <span class="radiolist_horizontal">
                                 <input type="radio" id="radio_<?php echo $idfield.'_'.$i ?>" name="PostAd[custom_field][<?php echo $idfield ?>]"
                                        value="<?php echo $option['id'] ?>" autocomplete="off">
                                 <label class="control-label" for="radio_<?php echo $idfield.'_'.$i ?>"><?php echo $option['label'] ?></label>
                             </span>

                                 <?php $i++; }
                             ?>
                         </div>
                         <di v class="help-block"></div>
                     </div>


             <?php }

             if($custom_field['custom_fields_type']['type'] == 'checkbox'){ ?>
                     <?php $idfield=$custom_field['custom_fields']['id']; ?>
                     <div class="radioitemlist field-postad-custom_field-<?php echo $idfield?>">
                         <label class="control-label"><?= $label; ?>:</label>
                         <input type="hidden" name="PostAd[custom_field][<?php echo $idfield; ?>]" value="">
                         <div data-label="<?php echo $label_val ?>" class="<?php echo $class_c ?>" id="postad-custom_field-<?php echo $idfield?>">
                             <?php
                             $i=0;
                             foreach($custom_field['custom_fields_options'] as $key1=>$option){ ?>
                                 <span class="checkboxlist_horizontal">
                                 <input type="checkbox" id="checkbox_<?php echo $idfield.'_'.$i ?>"
                                        name="PostAd[custom_field][<?php echo $idfield ?>][]"
                                        value="<?php echo $option['id'] ?>" autocomplete="off">
                                 <label class="control-label" for="checkbox_<?php echo $idfield.'_'.$i ?>"><?php echo $option['label'] ?></label>
                             </span>

                                 <?php $i++; }
                             ?>
                         </div>
                         <div class="help-block"></div>
                     </div>
             <?php }
             if($custom_field['custom_fields_type']['type'] == 'select'){ ?>
                 <?php
                 $selectlist=array();
                 foreach($custom_field['custom_fields_options'] as $key1=>$option){
                     $selectlist[$option['id']]=$option['label'];
                 }
                 echo $form->field($model, 'custom_field['.$custom_field['custom_fields']['id'].']')
                 ->dropDownList($selectlist,['class'=>$class,'data-label'=>$label_val])->label($label);
              }
         } ?>
<?php } ?>
