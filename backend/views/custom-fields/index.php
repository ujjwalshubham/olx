<?php

use backend\themes\classified\assets\FieldsAsset;
use common\models\CustomFieldTypes;
use common\models\CustomFieldOptions;
use common\models\CustomFields;
use common\models\Categories;
use common\models\CategoriesCustomFields;

FieldsAsset::register($this);

$this->title="Custom Fields";

$array_fields=array();
$fields=CustomFields::find()->all();
$k=0;
foreach($fields as $field){
    $type=CustomFieldTypes::find()->where(['id'=>$field->field_type_id])->one();
    $array_fields[$k]['type']=$type['data_type'];
    $array_fields[$k]['label']=$field->label;
    if($field->isRequired == 'true'){
        $array_fields[$k]['required']=true;
    }
    else{
        $array_fields[$k]['required']=false;
    }
    $array_fields[$k]['id']=$field->id;
    $field_categories=CategoriesCustomFields::find()->where(['field_id'=>$field->id])->all();
    $list=[];
    foreach($field_categories as $f_cat){
        $list[]=$f_cat->category_id;
    }
    if(is_array($list)){
        $list=implode(',',$list);
    }
    else{
        $list='';
    }
    $array_fields[$k]['maincat']= '12,13,14,15';
    $array_fields[$k]['services']= $list;

    $options=CustomFieldOptions::find()->where(['field_id'=>$field->id])->all();
    if(!empty($options)){
        $optionsmeta=array();
        $m=0;
        foreach($options as $option){
            $optionsmeta[$m]['id']=$option->id;
            $optionsmeta[$m]['title']=$option->label;
            $m++;
        }
        $array_fields[$k]['items']= $optionsmeta;
    }

    $k++;
}
$json_field=json_encode($array_fields);
$js="

var quickadL10n = {
    'csrf_token':'12232412',
    'custom_fields':'$json_field',
    'selector':{
        'all_selected':'All Category',
        'nothing_selected':'No category selected'
    },
    'saved':'Custom fields saved'
};

";
$this->registerJs($js,\yii\web\VIEW::POS_END);
?>
<!-- Partial Table -->
<div class="card">
    <div class="card-header">
        <h4>Custom Fields</h4>
    </div>
    <div class="card-block">
        <!-- /row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div>
                        <div id="quickad-tbs" class="wrap">
                            <div class="quickad-tbs-body">
                                <div class="panel panel-default quickad-main">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="quickad_custom_fields_per_service">Bind fields to categories</label>
                                                    <p class="help-block">When this setting is enabled you will be able to create category specific custom fields.</p>
                                                    <select class="form-control" name="quickad_custom_fields_per_service" id="quickad_custom_fields_per_service">
                                                        <option value="0">Disabled</option>
                                                        <option value="1" selected="selected">Enabled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <ul id="quickad-custom-fields" class="ui-sortable" style="position: relative">
                                        </ul>
                                        <div id="quickad-js-add-fields">
                                            <button class="btn btn-default quickad-margin-bottom-sm quickad-margin-right-sm" data-type="text-field"><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Text Field</button>
                                            <button class="btn btn-default quickad-margin-bottom-sm quickad-margin-right-sm" data-type="textarea"><i class="glyphicon glyphicon-plus"></i> Text Area</button>
                                            <button class="btn btn-default quickad-margin-bottom-sm quickad-margin-right-sm" data-type="checkboxes"><i class="glyphicon glyphicon-plus"></i> Checkbox Group</button>
                                            <button class="btn btn-default quickad-margin-bottom-sm quickad-margin-right-sm" data-type="radio-buttons"><i class="glyphicon glyphicon-plus"></i> Radio Button Group</button>
                                            <button class="btn btn-default quickad-margin-bottom-sm quickad-margin-right-sm" data-type="drop-down"><i class="glyphicon glyphicon-plus"></i> Drop Down</button>
                                        </div>
                                        <p class="help-block">HTML allowed in textarea.</p>
                                        <ul id="quickad-templates" style="display:none">
                                            <li data-type="text-field">
                                                <div class="quickad-flexbox">
                                                    <div class="quickad-flex-cell"> <i title="Reorder" class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move"></i> </div>
                                                    <div class="quickad-flex-cell" style="width: 100%">
                                                        <p><b>Text Field</b><a class="quickad-js-delete text-danger quickad-margin-left-sm" href="javascript:void(0)" title="Remove field"><i class="fa fa-trash-o" aria-hidden="true"></i></a></p>
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="quickad-label form-control" type="text" value="" placeholder="Enter a label">
                                                                    <label class="input-group-addon">
                                                                        <label class="css-input css-checkbox css-checkbox-default m-t-0 m-b-0">
                                                                            <input type="checkbox" id="TextFieldReq" class="quickad-required">
                                                                            <span></span> Required field </label>
                                                                        <i class="visible-xs-inline-block glyphicon glyphicon-warning"></i>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="btn-group quickad-services-holder quickad-margin-top-screenxs-sm">
                                                                    <button class="btn btn-default btn-block dropdown-toggle quickad-flexbox" data-toggle="dropdown">
                                                                        <div class="quickad-flex-cell"> <i class=" fa fa-tag quickad-margin-right-md"></i> </div>
                                                                        <div class="quickad-flex-cell text-left" style="width: 100%"> <span class="quickad-js-count">No category selected</span> </div>
                                                                        <div class="quickad-flex-cell">
                                                                            <div class="quickad-margin-left-md"><span class="caret"></span></div>
                                                                        </div>
                                                                    </button>
                                                                    <?php echo $this->render('_category_dropdown',['categories'=>$categories]);?>
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-warning quickad_language_translation" data-custom-field-id="" data-category-type="custom-field"> <span class="ladda-label"><i class="fa fa-language"></i> Language Translation</span></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </li>
                                            <li data-type="textarea">
                                                <div class="quickad-flexbox">
                                                    <div class="quickad-flex-cell"> <i title="Reorder" class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move"></i> </div>
                                                    <div class="quickad-flex-cell" style="width: 100%">
                                                        <p><b>Text Area</b>
                                                            <a class="quickad-js-delete text-danger quickad-margin-left-sm" href="javascript:void(0)" title="Remove field">
                                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                            </a>
                                                        </p>
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="quickad-label form-control" type="text" value="" placeholder="Enter a label">
                                                                    <label class="input-group-addon">
                                                                        <label class="css-input css-checkbox css-checkbox-default m-t-0 m-b-0">
                                                                            <input type="checkbox" id="TextAreaReq" class="quickad-required">
                                                                            <span></span> Required field </label>
                                                                        <i class="visible-xs-inline-block glyphicon glyphicon-warning"></i>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="btn-group quickad-services-holder quickad-margin-top-screenxs-sm">
                                                                    <button class="btn btn-default btn-block dropdown-toggle quickad-flexbox" data-toggle="dropdown">
                                                                        <div class="quickad-flex-cell"> <i class="fa fa-tag quickad-margin-right-md"></i> </div>
                                                                        <div class="quickad-flex-cell text-left" style="width: 100%"> <span class="quickad-js-count">No category selected</span> </div>
                                                                        <div class="quickad-flex-cell">
                                                                            <div class="quickad-margin-left-md"><span class="caret"></span></div>
                                                                        </div>
                                                                    </button>
                                                                    <?php echo $this->render('_category_dropdown',['categories'=>$categories]);?>
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-warning quickad_language_translation" data-custom-field-id="" data-category-type="custom-field"> <span class="ladda-label"><i class="fa fa-language"></i> Language Translation</span></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </li>
                                            <li data-type="checkboxes">
                                                <div class="quickad-flexbox">
                                                    <div class="quickad-flex-cell"> <i title="Reorder" class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move"></i> </div>
                                                    <div class="quickad-flex-cell" style="width: 100%">
                                                        <p><b>Checkbox Group</b>
                                                            <a class="quickad-js-delete text-danger quickad-margin-left-sm" href="javascript:void(0)"
                                                               title="Remove field"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                                            </a>
                                                        </p>
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="quickad-label form-control" type="text" value="" placeholder="Enter a label">
                                                                    <label class="input-group-addon">
                                                                        <label class="css-input css-checkbox css-checkbox-default m-t-0 m-b-0">
                                                                            <input type="checkbox" id="CheckboxReq" class="quickad-required">
                                                                            <span></span> Required field </label>
                                                                        <i class="visible-xs-inline-block glyphicon glyphicon-warning"></i>
                                                                    </label>
                                                                </div>
                                                                <ul class="quickad-items quickad-margin-top-sm">
                                                                </ul>
                                                                <button class="btn btn-sm btn-default" data-type="checkboxes-item"> <i class="glyphicon glyphicon-plus"></i> Checkbox </button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="btn-group quickad-services-holder quickad-margin-top-screenxs-sm">
                                                                    <button class="btn btn-default btn-block dropdown-toggle quickad-flexbox" data-toggle="dropdown">
                                                                        <div class="quickad-flex-cell"> <i class="fa fa-tag quickad-margin-right-md"></i> </div>
                                                                        <div class="quickad-flex-cell text-left" style="width: 100%"> <span class="quickad-js-count">No category selected</span> </div>
                                                                        <div class="quickad-flex-cell">
                                                                            <div class="quickad-margin-left-md"><span class="caret"></span></div>
                                                                        </div>
                                                                    </button>
                                                                    <?php echo $this->render('_category_dropdown',['categories'=>$categories]);?>
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-warning quickad_language_translation"
                                                                        data-custom-field-id=""
                                                                        data-category-type="custom-field">
                                                                    <span class="ladda-label"><i class="fa fa-language"></i> Language Translation</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </li>
                                            <li data-type="radio-buttons">
                                                <div class="quickad-flexbox">
                                                    <div class="quickad-flex-cell"> <i title="Reorder" class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move"></i> </div>
                                                    <div class="quickad-flex-cell" style="width: 100%">
                                                        <p><b>Radio Button Group</b><a class="quickad-js-delete text-danger quickad-margin-left-sm" href="javascript:void(0)" title="Remove field"><i class="fa fa-trash-o" aria-hidden="true"></i></a></p>
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="quickad-label form-control" type="text" value="" placeholder="Enter a label">
                                                                    <label class="input-group-addon">
                                                                        <label class="css-input css-checkbox css-checkbox-default m-t-0 m-b-0">
                                                                            <input type="checkbox" id="RadioReq" class="quickad-required">
                                                                            <span></span> Required field </label>
                                                                        <i class="visible-xs-inline-block glyphicon glyphicon-warning"></i>
                                                                    </label>
                                                                </div>
                                                                <ul class="quickad-items quickad-margin-top-sm">
                                                                </ul>
                                                                <button class="btn btn-sm btn-default" data-type="radio-buttons-item"> <i class="glyphicon glyphicon-plus"></i> Radio Button </button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="btn-group quickad-services-holder quickad-margin-top-screenxs-sm">
                                                                    <button class="btn btn-default btn-block dropdown-toggle quickad-flexbox" data-toggle="dropdown">
                                                                        <div class="quickad-flex-cell"> <i class="fa fa-tag quickad-margin-right-md"></i> </div>
                                                                        <div class="quickad-flex-cell text-left" style="width: 100%"> <span class="quickad-js-count">No category selected</span> </div>
                                                                        <div class="quickad-flex-cell">
                                                                            <div class="quickad-margin-left-md"><span class="caret"></span></div>
                                                                        </div>
                                                                    </button>
                                                                    <?php echo $this->render('_category_dropdown',['categories'=>$categories]);?>

                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-warning quickad_language_translation" data-custom-field-id="" data-category-type="custom-field"> <span class="ladda-label"><i class="fa fa-language"></i> Language Translation</span></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </li>
                                            <li data-type="drop-down">
                                                <div class="quickad-flexbox">
                                                    <div class="quickad-flex-cell"> <i title="Reorder" class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move"></i> </div>
                                                    <div class="quickad-flex-cell" style="width: 100%">
                                                        <p><b>Drop Down</b><a class="quickad-js-delete text-danger quickad-margin-left-sm" href="javascript:void(0)" title="Remove field"><i class="fa fa-trash-o" aria-hidden="true"></i></a></p>
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="quickad-label form-control" type="text" value="" placeholder="Enter a label">
                                                                    <label class="input-group-addon">
                                                                        <label class="css-input css-checkbox css-checkbox-default m-t-0 m-b-0">
                                                                            <input type="checkbox" id="DropDownReq" class="quickad-required">
                                                                            <span></span> Required field </label>
                                                                        <i class="visible-xs-inline-block glyphicon glyphicon-warning"></i>
                                                                    </label>
                                                                </div>
                                                                <ul class="quickad-items quickad-margin-top-sm">
                                                                </ul>
                                                                <button class="btn btn-sm btn-default" data-type="drop-down-item"> <i class="glyphicon glyphicon-plus"></i> Option </button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="btn-group quickad-services-holder quickad-margin-top-screenxs-sm">
                                                                    <button class="btn btn-default btn-block dropdown-toggle quickad-flexbox" data-toggle="dropdown">
                                                                        <div class="quickad-flex-cell"> <i class="fa fa-tag quickad-margin-right-md"></i> </div>
                                                                        <div class="quickad-flex-cell text-left" style="width: 100%"> <span class="quickad-js-count">No category selected</span> </div>
                                                                        <div class="quickad-flex-cell">
                                                                            <div class="quickad-margin-left-md"><span class="caret"></span></div>
                                                                        </div>
                                                                    </button>
                                                                    <?php echo $this->render('_category_dropdown',['categories'=>$categories]);?>

                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-warning quickad_language_translation" data-custom-field-id="" data-category-type="custom-field"> <span class="ladda-label"><i class="fa fa-language"></i> Language Translation</span></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </li>
                                            <li data-type="checkboxes-item">
                                                <div class="quickad-flexbox">
                                                    <div class="quickad-flex-cell quickad-vertical-middle"> <i title="Reorder" class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move"></i> </div>
                                                    <div class="quickad-flex-cell quickad-vertical-middle" style="width: 100%">
                                                        <input class="form-control" type="text" value="" placeholder="Enter a label">
                                                    </div>
                                                    <div class="quickad-flex-cell quickad-vertical-middle"> <a class="quickad_itmes_translation fa fa-language text-warning quickad-margin-left-sm" href="javascript:void(0)" title="Language Translation item"></a> </div>
                                                    <div class="quickad-flex-cell quickad-vertical-middle"> <a class="quickad-option-delete text-danger quickad-margin-left-sm" href="javascript:void(0)" title="Remove item"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>
                                                </div>
                                            </li>
                                            <li data-type="radio-buttons-item">
                                                <div class="quickad-flexbox">
                                                    <div class="quickad-flex-cell quickad-vertical-middle"> <i title="Reorder" class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move"></i> </div>
                                                    <div class="quickad-flex-cell quickad-vertical-middle" style="width: 100%">
                                                        <input class="form-control" type="text" value="" placeholder="Enter a label">
                                                    </div>
                                                    <div class="quickad-flex-cell quickad-vertical-middle"> <a class="quickad_itmes_translation fa fa-language text-warning quickad-margin-left-sm" href="javascript:void(0)" title="Language Translation item"></a> </div>
                                                    <div class="quickad-flex-cell quickad-vertical-middle"> <a class="quickad-option-delete text-danger quickad-margin-left-sm" href="javascript:void(0)" title="Remove item"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>
                                                </div>
                                            </li>
                                            <li data-type="drop-down-item">
                                                <div class="quickad-flexbox">
                                                    <div class="quickad-flex-cell quickad-vertical-middle"> <i title="Reorder" class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move"></i> </div>
                                                    <div class="quickad-flex-cell quickad-vertical-middle" style="width: 100%">
                                                        <input class="form-control" type="text" value="" placeholder="Enter a label">
                                                    </div>
                                                    <div class="quickad-flex-cell quickad-vertical-middle"> <a class="quickad_itmes_translation fa fa-language text-warning quickad-margin-left-sm" href="javascript:void(0)" title="Language Translation item"></a> </div>
                                                    <div class="quickad-flex-cell quickad-vertical-middle"> <a class="quickad-option-delete text-danger quickad-margin-left-sm" href="javascript:void(0)" title="Remove item"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="panel-footer">
                                        <button id="ajax-send-custom-fields" type="submit" class="btn btn-lg btn-success ladda-button" data-style="zoom-in" data-spinner-size="40"><span class="ladda-label">Save</span><span class="ladda-spinner"></span>
                                            <div class="ladda-progress" style="width: 0px;"></div>
                                        </button>
                                        <button class="btn btn-lg btn-default" type="reset">Reset</button>
                                    </div>
                                </div>
                            </div>
                            <div id="quickad-alert" class="quickad-alert"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- .card-block -->
</div>
<!-- .card -->
<!-- End Partial Table -->

<!-- /.Language Translation modal -->
<div id="modal_LangTranslation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Edit Language Translation</h4>
                </div>
                <div class="modal-body">
                    <div class="loader" style="text-align: center;">
                        <img src="../loading.gif"/>
                    </div>
                    <div class="form-horizontal" id="displayData">
                        <!--Dynamic form fields-->
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="saveCustomEditLanguage">Save</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="Options_LangTranslation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Edit Language Translation</h4>
                </div>
                <div class="modal-body">
                    <div class="loader" style="text-align: center;">
                        <img src="../loading.gif"/>
                    </div>
                    <div class="form-horizontal" id="displayData">
                        <!--Dynamic form fields-->
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="saveEditLanguage">Save</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Language Translation modal -->