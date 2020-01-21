<?php
use yii\widgets\ActiveForm;
$baseurl=\yii\helpers\Url::to('@frontendUrl');

?>

<?php $form = ActiveForm::begin(['id' => 'logosetting',
    'action'=>['settings/logo-watermark'],
    'options' => ['role' => 'form', 'enctype' => 'multipart/form-data']]); ?>
<!-- Favicon upload-->
    <div class="form-group">

        <label class="control-label">Favicon Icon<code>*</code></label>
        <div class="screenshot"><img class="redux-option-image" id="favicon_uploader"
                                     src="<?php echo $baseurl.$model['favicon_icon']; ?>" alt="" target="_blank"
                                     rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%"></div>
        <input class="form-control input-sm" type="file" name="favicon"
               onchange="readURL(this,'favicon_uploader')">
        <span class="help-block">Ideal Size 16x16 PX</span>
    </div>

    <!-- Site Logo upload-->
    <div class="form-group">
        <label class="control-label">Logo<code>*</code></label>
        <div class="screenshot"><img class="redux-option-image" id="image_logo_uploader"
                                     src="<?php echo $baseurl.$model['logo']; ?>" alt="" target="_blank"
                                     rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%"></div>
        <input class="form-control input-sm" type="file" name="file" onchange="readURL(this,'image_logo_uploader')">
        <span class="help-block">Ideal Size 168x57 PX</span>
    </div>
    <!-- Site Logo upload-->

    <!-- Home Banner upload-->
    <div class="form-group">
        <label class="control-label">Home Banner<code>*</code></label>
        <div class="screenshot"><img class="redux-option-image" id="home_banner"
                                     src="<?php echo $baseurl.$model['home_banner']; ?>" alt="" target="_blank"
                                     rel="external" width="400px"></div>
        <input class="form-control input-sm" type="file" name="banner" onchange="readURL(this,'home_banner')">
    </div>
    <!-- Home Banner upload-->

    <!-- Watermark Image upload-->
    <div class="form-group">
        <label class="control-label">Watermark Image</label>
        <div class="screenshot">
            <img class="redux-option-image" id="watermark_logo"
                 src="<?php echo $baseurl.$model['watermark_image']; ?>"
                 alt=""  target="_blank" rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%">
        </div>
        <input class="form-control input-sm" type="file" name="watermark" onchange="readURL(this,'watermark_logo')">
        <span class="help-block">Must be png</span>
    </div>
    <!-- Watermark Image upload-->

    <!-- Admin Logo upload-->
    <div class="form-group">
        <label class="control-label">Admin Logo</label>
        <div class="screenshot"><img class="redux-option-image" id="adminlogo"
                                     src="<?php echo $baseurl.$model['admin_logo']; ?>" alt="" target="_blank"
                                     rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%"></div>
        <input class="form-control input-sm" type="file" name="adminlogo" onchange="readURL(this,'adminlogo')">
        <span class="help-block">Ideal Size 235x62 PX</span>
    </div>

    <!-- Admin Logo upload-->
    <div class="panel-footer">
        <button name="logo_watermark" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
        <button class="btn btn-default" type="reset">Reset</button>
    </div>

<?php ActiveForm::end(); ?>