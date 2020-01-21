<?php
use common\models\Categories;
    $cat_lists=\common\components\AppHelper::getCategoryTreeIDs($parent);
?>

<h4 class="quickad-block-head">
    <?php if(!empty($categories)) { ?>
        <?php if($parent == 'all'){ ?>
            <span class="quickad-category-title">All Categories</span>
        <?php } else{ ?>
                <ul class="select-category list-inline">
                    <?php foreach($cat_lists as $k=>$list){ ?>
                        <?php if($k == 0) { ?>
                            <li id="main-category-text"><?php echo \common\components\AppHelper::getCategoryTitle($list);?></li>
                        <?php } else { ?>
                            <li id="sub-category-text"><?php echo \common\components\AppHelper::getCategoryTitle($list);?></li>
                        <?php } ?>
                    <?php }?>
                        <?php if($parent > 0) { ?>
                        <li id="sub-category-text"><?php echo \common\components\AppHelper::getCategoryTitle($parent);?></li>
                        <?php } ?>
                </ul>
            <div class="pull-right">
                <a class="btn btn-success" href="javascript:void(0)" data-toggle="slidePanel"
                   data-url="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['categories/create','id'=>$parent]);?>">
                    <span class="ladda-label"><i class="glyphicon glyphicon-plus"></i>Add Sub-Category</span>
                </a>
            </div>

        <?php }?>


    <?php }else{ ?>
        <?php if($parent == 'all'){ ?>
            <span class="quickad-category-title">All Categories</span>
        <?php } else{ ?>
            <ul class="select-category list-inline">
                <?php foreach($cat_lists as $k=>$list){ ?>
                    <?php if($k == 0) { ?>
                        <li id="main-category-text"><?php echo \common\components\AppHelper::getCategoryTitle($list);?></li>
                    <?php } else { ?>
                        <li id="sub-category-text"><?php echo \common\components\AppHelper::getCategoryTitle($list);?></li>
                    <?php } ?>
                <?php }?>
                <?php if($parent > 0) { ?>
                    <li id="sub-category-text"><?php echo \common\components\AppHelper::getCategoryTitle($parent);?></li>
                <?php } ?>
            </ul>

            <div class="pull-right">
                <a class="btn btn-success" href="javascript:void(0)" data-toggle="slidePanel"
                   data-url="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['categories/create','id'=>$parent]);?>">
                    <span class="ladda-label"><i class="glyphicon glyphicon-plus"></i>Add Sub-Category</span>
                </a>
            </div>

        <?php }?>
    <?php }?>
</h4>
<form method="post" id="new-subcategory-form" style="display: none">
    <div class="form-group quickad-margin-bottom-md">
        <div class="form-field form-required">
            <label for="new-subcategory-name">Title</label>
            <input class="form-control" id="new-subcategory-name" type="text" name="name" required=""/>
            <input type="hidden" id="cat-id" name="cat_id" value="0">
        </div>
    </div>
    <div class="text-right">
        <button type="submit" class="btn btn-success confirm">Save</button>
        <button type="button" id="cancel-button" class="btn btn-default">Cancel</button>
    </div>
</form>

<p class="quickad-margin-top-xlg no-result" style="display: none;">No services found. Please add services</p>

<div class="quickad-margin-top-xlg" id="ab-services-list">
    <?php if(!empty($categories)) { ?>
        <div class="panel-group ui-sortable" id="services_list" role="tablist" aria-multiselectable="true">
            <div class="panel-group ui-sortable" id="services_list" role="tablist" aria-multiselectable="true">

                <?php foreach($categories as $category){?>
                    <?php if($category->status == Categories::STATUS_ACTIVE){
                        $header_class="panel-success";
                    }
                    elseif($category->status == Categories::STATUS_DELETED){
                        $header_class="panel-danger";
                    }
                    else{
                        $header_class="panel-default";
                    }?>
                    <div class="panel <?php echo $header_class; ?> quickad-js-collapse quickad-category-item" data-category-id="<?php echo $category->id; ?>" data-service-id="<?php echo $category->id; ?>">
                        <div class="panel-heading" role="tab" id="s_<?php echo $category->id; ?>">
                            <div class="row">
                                <div class="col-sm-8 col-xs-10">
                                    <div class="quickad-flexbox">
                                        <div class="quickad-flex-cell quickad-vertical-middle" style="width: 1%">
                                            <i class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move ui-sortable-handle" title="Reorder"></i>
                                        </div>
                                        <div class="quickad-flex-cell quickad-vertical-middle">
                                            <a role="button" class="panel-title collapsed quickad-js-service-title" data-toggle="collapse" data-parent="#services_list" href="#service_<?php echo $category->id; ?>" aria-expanded="false" aria-controls="service_<?php echo $category->id; ?>">
                                                <?php echo $category->title; ?> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-xs-2">
                                    <div class="quickad-flexbox">
                                        <div class="quickad-flex-cell quickad-vertical-middle text-right" style="width: %;font-size: 18px;">
                                            <a href="#" class="fa fa-language text-default quickad-margin-horizontal-xs quickad-cat-lang-edit" data-category-id="<?php echo $category->id; ?>"
                                               data-category-type="main" title="Edit-language"></a>
                                        </div>
                                        <div class="quickad-flex-cell quickad-vertical-middle text-right" style="width: 1%;font-size: 18px;">
                                            <?php $editUrl=Yii::$app->urlManager->createAbsoluteUrl(['categories/update','id'=>$category->id]); ?>
                                            <a href="javascript:void(0)" class="fa fa-pencil-square-o quickad-margin-horizontal-xs quickad-js-edit" data-toggle="slidePanel"
                                               data-url="<?php echo $editUrl ?>" title="Edit"></a>
                                        </div>
                                        <!--<div class="quickad-flex-cell quickad-vertical-middle text-right" style="width: 1%">
                                            <label class="css-input css-checkbox css-checkbox-default m-t-0 m-b-0">
                                                <input type="checkbox" id="checkbox1" name="check-all" value="1" class="service-checker"><span></span>
                                            </label>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="service_<?php echo $category->id; ?>" class="panel-collapse collapse" role="tabpanel" style="height: 0">
                        <div class="panel-body">
                            <form method="post" id="1">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="title_<?php echo $category->id; ?>">Title</label>
                                            <input name="title" value="<?php echo $category->title; ?>" id="title_<?php echo $category->id; ?>" class="form-control" type="text">

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="slug_<?php echo $category->id; ?>">Slug</label>
                                            <input name="slug" value="<?php echo $category->slug; ?>" id="slug_<?php echo $category->id; ?>" class="form-control" type="text">

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="photo_<?php echo $category->id; ?>">Photo field Enable/Disable</label>
                                            <select name="photo_show" class="form-control">
                                                <option value="1">Enable</option>
                                                <option value="0">Disable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="price_<?php echo $category->id; ?>">Price Enable/Disable</label>
                                            <select name="price_show" class="form-control">
                                                <option value="1">Enable</option>
                                                <option value="0">Disable</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="picture_<?php echo $category->id; ?>">Icon Image Url</label>
                                            <input name="picture" value="https://img.icons8.com/color/64/000000/cycling-mountain-bike--v2.png" id="picture_1" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <input name="id" value="<?php echo $category->id; ?>" type="hidden">
                                    <button type="button" class="show btn btn-lg btn-warning quickad-cat-lang-edit" data-category-id="<?php echo $category->id; ?>" data-category-type="sub"> <span class="ladda-label"><i class="fa fa-language"></i> Edit Language</span></button>
                                    <button type="button" class="btn btn-lg btn-success ladda-button ajax-subcat-edit" data-style="zoom-in" data-spinner-size="40" onclick="editSubCat(<?php echo $category->id; ?>);"><span class="ladda-label">Save</span></button>
                                    <button class="btn btn-lg btn-default js-reset" type="reset">Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    <?php } else{
        echo "No sub category found.";
    }?>
</div>
