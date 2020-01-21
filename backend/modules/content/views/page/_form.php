<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this  yii\web\View
 * @var $model common\models\Page
 */
\backend\themes\classified\assets\EditorAsset::register($this);

$js="     
   (function() {   
       $(function() {
            var preview, editor, mobileToolbar, toolbar, allowedTags;
            Simditor.locale = 'en-US';
            toolbar = ['bold','italic','underline','fontScale','|','ol','ul','blockquote','table','link'];
            mobileToolbar = [\"bold\", \"italic\", \"underline\", \"ul\", \"ol\"];
            if (mobilecheck()) {
                toolbar = mobileToolbar;
            }
            allowedTags = ['br','span','a','img','b','strong','i','strike','u','font','p','ul','ol','li','blockquote','pre','h1','h2','h3','h4','hr','table'];
            editor = new Simditor({
                textarea: $('#pageContent'),
                placeholder: 'Description...',
                toolbar: toolbar,
                pasteImage: false,
                defaultImage: '../js/plugins/simditor/images/image.png',
                upload: false,
                allowedTags: allowedTags
            });
            
            preview = $('#preview');
            if (preview.length > 0) {
                return editor.on('valuechanged', function(e) {
                    return preview.html(editor.getValue());
                });
            }
        });
    }).call(this);
";
$this->registerJs($js,\yii\web\VIEW::POS_END);
?>
<style>
    .simditor .simditor-toolbar > ul > li > .toolbar-item span.simditor-icon{
        line-height: 40px;
    }
</style>
<header class="slidePanel-header overlay">
    <div class="overlay-panel overlay-background vertical-align">
        <div class="service-heading">
            <h2><?php echo $type; ?> Page</h2>
        </div>
        <div class="slidePanel-actions">
            <div class="btn-group-flat">
                <button type="button" class="submit_form_tick btn btn-floating btn-warning btn-sm waves-effect waves-float waves-light margin-right-10" id="post_sidePanel_data">
                    <i class="icon ion-android-done" aria-hidden="true"></i></button>
                <button type="button" class="btn btn-pure btn-inverse slidePanel-close icon ion-android-close font-size-20" aria-hidden="true"></button>
            </div>
        </div>
    </div>
</header>
<div class="slidePanel-inner">
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['id'=>'sidePanel_form','action'=>'javascript:void(0)',
            'options'=>['data-ajax-action'=>$ajaxurl]]) ?>

            <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?php echo $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <label>Content:</label>
                <textarea name="Page[body]" id="pageContent" rows="6" type="text"
                          class="form-control"><?php echo $model->body; ?></textarea>
            </div>

            <?php echo $form->field($model, 'view')->textInput(['maxlength' => true]) ?>

            <?php echo $form->field($model, 'status')->checkbox() ?>

            <div class="form-group" style="display:none;">
                <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
    <?php ActiveForm::end() ?>
    </div>
</div>
