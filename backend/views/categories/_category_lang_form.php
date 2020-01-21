<input type="hidden" id="category_id" value="<?php echo $category->id; ?>">
<input type="hidden" id="category_type" value="<?php echo $category_type; ?>">
<?php
use common\models\CategoriesLang;
$lanagages=\common\models\Languages::find()->where(['status'=>1])->andwhere(['!=','code','en'])->all();

foreach($lanagages as $language){
    $category_lang=CategoriesLang::find()->where(['category_id'=>$category->id,'locale'=>$language->code])->one();
    $title='';$slug='';
    if(!empty($category_lang)){
        $title=$category_lang->title;
        $slug=$category_lang->slug;
    }
?>
<div class="row translate_row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $language->title; ?></label>
            <div class="col-md-9">
                <input name="title" type="text" value="<?php echo $title; ?>" class="form-control cat_title" placeholder="In <?php echo $language->title; ?>">
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label class="col-md-3 control-label">Slug</label>
            <div class="col-md-9">
                <input name="slug" type="text" value="<?php echo $slug; ?>" class="form-control cat_slug" placeholder="Slug">
            </div>
        </div>
    </div>
    <input name="lang_code" type="hidden" class="lang_code" value="<?php echo $language->code; ?>">
</div>
<?php } ?>