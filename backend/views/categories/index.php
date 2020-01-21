<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Categories;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Partial Table -->
<div class="card">
    <div class="card-header">
        <h4>Categories</h4>
        <div class="pull-right">
            <?php echo Html::a(Yii::t('backend', 'Add New {modelClass}', [
                'modelClass' => 'Category',
            ]), 'javascript:void(0)', ['class' => 'btn btn-success','data-toggle'=>'slidePanel',
                'data-url'=>Yii::$app->urlManager->createAbsoluteUrl(['categories/create'])]); ?>
        </div>
    </div>
    <div class="card-block">
        <?php Pjax::begin([ 'id' => 'pjax-grid', 'enablePushState'=>false, ]); ?>
        <!-- /row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div id="quickad-tbs" class="wrap">
                        <div class="quickad-tbs-body">
                            <div class="row">
                                <div id="quickad-sidebar" class="col-sm-4">
                                    <div id="quickad-categories-list" class="quickad-nav">
                                        <div class="quickad-nav-item active quickad-category-item quickad-js-all-services">
                                            <div class="quickad-padding-vertical-xs">All Categories</div>
                                        </div>
                                        <?php if(!empty($categories)){ ?>
                                            <ul id="quickad-category-item-list" class="ui-sortable">
                                                <?php foreach($categories as $category){ ?>
                                                    <?php if($category->status == Categories::STATUS_ACTIVE){ $class='';}
                                                         else{$class="cat_status_inactive";} ?>
                                                    <li class="quickad-nav-item quickad-category-item <?php echo $class; ?>" data-category-id="<?php echo $category->id; ?>">
                                                        <div class="quickad-flexbox">
                                                            <div class="quickad-flex-cell quickad-vertical-middle" style="width: 1%">
                                                                <i class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move ui-sortable-handle" title="Reorder"></i>

                                                            </div>
                                                            <div class="quickad-flex-cell quickad-vertical-middle">
                                                            <span class="displayed-value" style="display: inline;">
                                                                <i id="quickad-cat-icon" class="quickad-margin-right-sm pe-7s-monitor"
                                                                   title="<?php echo $category->title; ?>"></i> <?php echo $category->title; ?>                                                           </span>
                                                            </div>
                                                            <div class="quickad-flex-cell quickad-vertical-middle" style="width: 1%;font-size: 18px;">
                                                                <a href="#" class="fa fa-language text-default quickad-margin-horizontal-xs quickad-cat-lang-edit" data-category-id="<?php echo $category->id; ?>"
                                                                   data-category-type="main" title="Edit-language"></a>
                                                            </div>
                                                            <div class="quickad-flex-cell quickad-vertical-middle" style="width: 1%;font-size: 18px;">
                                                                <?php $editUrl=Yii::$app->urlManager->createAbsoluteUrl(['categories/update','id'=>$category->id]); ?>
                                                                <a href="javascript:void(0)" class="fa fa-pencil-square-o quickad-margin-horizontal-xs quickad-js-edit" data-toggle="slidePanel"
                                                                data-url="<?php echo $editUrl ?>" title="Edit"></a>
                                                            </div>
                                                           <!-- <div class="quickad-flex-cell quickad-vertical-middle" style="width: 1%;font-size: 18px;">

                                                                <button type="button" class="text-danger quickad-js-delete" style="border:none;background:  transparent;"><i class="fa fa-trash-o"></i></button>
                                                            </div>-->
                                                        </div>
                                                    </li>

                                                <?php }?>
                                             </ul>
                                        <?php }?>
                                    </div>


                                </div>

                                <div id="quickad-services-wrapper" class="col-sm-8">
                                    <div class="panel panel-default quickad-main">
                                        <div class="panel-body">
                                            <div id="ab-services-list-main">
                                            <?php echo $this->render('_subcategory_append',['categories'=>$subcategories,'parent'=>'all']);?>
                                            </div>
                                            <!--<div class="text-right">
                                                <button type="button" id="quickad-delete" class="btn btn-danger ladda-button"
                                                        data-spinner-size="40" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span></button>
                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="quickad-alert" class="quickad-alert"></div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->
        <?php Pjax::end(); ?>
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
                    <button type="button" class="btn btn-success" id="saveEditLanguage">Save</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Language Translation modal -->