<?php
use common\models\Categories;
?>
<ul class="dropdown-menu quickad-entity-selector" style="overflow-y: scroll;height: 300px">
    <li> <a class="checkbox checkbox-success" href="javascript:void(0)">
            <input type="checkbox" class="quickad-check-all-entities" value="any">
            <label>All Category</label>
        </a> </li>
    <?php foreach($categories as $category){ ?>
        <li class="main-category"> <a class="checkbox checkbox-success" href="javascript:void(0)">
            <input type="checkbox" class="quickad-js-check-entity"  value="<?php echo $category->id; ?>" data-title="<?php echo $category->title; ?>">
            <label><i class="pe-7s-monitor"></i><?php echo $category->title; ?></label>
        </a>
        <?php $subcategories=Categories::find()->where(['parent_id'=>$category->id])->all();
        if(!empty($subcategories)){
            echo ' <ul style="margin-left: 28px;">';
            foreach($subcategories as $subcategory){ ?>
                <li class="subcategory">
                    <a class="checkbox checkbox-success" href="javascript:void(0)">
                        <input type="checkbox" class="quickad-js-check-sub-entity" value="<?php echo $subcategory->id; ?>" data-title="<?php echo $subcategory->title; ?>">
                        <label><?php echo $subcategory->title; ?></label>
                    </a>
                </li>
            <?php }
            echo ' </ul>';
        } else{ ?>
            <ul style="margin-left: 28px;">
                <li class="subcategory" style="display: none">
                    <a class="checkbox checkbox-success" href="javascript:void(0)">
                        <input type="checkbox" class="quickad-js-check-sub-entity"  value="<?php echo $category->id; ?>" data-title="<?php echo $category->title; ?>">
                        <label><?php echo $category->title; ?></label>
                    </a>
                </li>
            </ul>
        <?php }?>
        </li>
    <?php } ?>
</ul>