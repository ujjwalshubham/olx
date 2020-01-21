<input type="hidden" id="category_id" value="<?php echo $optionfield->id; ?>">
<input type="hidden" id="category_type" value="<?php echo $category_type; ?>">

<?php
use common\models\CustomFieldsLang;
$lanagages=\common\models\Languages::find()->where(['status'=>1])->andwhere(['!=','code','en'])->all();

foreach($lanagages as $language){
    $category_lang=\common\models\CustomFieldOptionsLang::find()->where(['field_option_id'=>$optionfield->id,
        'locale'=>$language->code])->one();
    $label='';
    if(!empty($category_lang)){
        $label=$category_lang->label;
    }
    ?>
    <div class="row translate_row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="col-md-3 control-label"><?php echo $language->title; ?></label>
                <div class="col-md-9">
                    <input name="title" type="text" value="<?php echo $label; ?>"
                           class="form-control cat_title" data-lang-code="<?php echo $language->code; ?>"
                           placeholder="In <?php echo $language->title; ?>">
                </div>
            </div>
        </div>
        <input name="lang_code" type="hidden" class="lang_code" value="<?php echo $language->code; ?>">
    </div>
<?php } ?>