<?php
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(['id'=>'new-category-form','action'=>['categories/create'],
    'options'=>['style'=>"display: none"]]) ?>
    <div class="form-group quickad-margin-bottom-md">
        <div class="form-field form-required">
            <label for="quickad-category-name">Title</label>
            <input class="form-control" id="quickad-category-name" type="text" name="name" required=""/>
        </div>
        <div class="form-field form-required">
            <label for="quickad-category-slug">Slug</label>
            <input class="form-control" id="quickad-category-slug" type="text" name="slug" required=""/>
        </div>
        <!--<div class="form-field form-required">
            <label for="quickad-category-name">Icon class for Category</label>
            <input class="form-control" id="quickad-category-icon" type="text" name="icon" placeholder="fa fa-usd" required=""/>
        </div>
        <div class="form-field form-required">
            <label for="quickad-category-name">Picture url</label>
            <input class="form-control" id="quickad-category-icon" type="text" name="picture"/>
        </div>-->
    </div>
    <div class="text-right">
        <button style="display:none" type="submit" class="btn btn-success confirm category_submit_btn">Save</button>
        <a href="javascript:void(0);" class="btn btn-success confirm category_submit">Save</a>
        <button type="button" id="cancel-button" class="btn btn-default">Cancel</button>
    </div>
<?php ActiveForm::end() ?>