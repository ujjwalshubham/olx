<?php
use yii\helpers\Url;

$baselink = Url::to('@backendUrl').'/settings';
?>
<ul class="quickad-nav" role="tablist">
    <?php

    ?>
    <li onclick="location.href='<?php echo $baselink.'/index' ?>'" id="index" class="quickad-nav-item <?php echo ($tabview == 'general')? 'active' : '' ?>">
       General
    </li>
    <li onclick="location.href='<?php echo $baselink.'/logo-watermark' ?>'" id="logo_watermark"
        class="quickad-nav-item <?php echo ($tabview == 'logo_watermark')? 'active' : '' ?>">
        Logo / Watermark
    </li>
    <li onclick="location.href='<?php echo $baselink.'/ad-post' ?>'" id="ad_post" class="quickad-nav-item <?php echo ($tabview == 'ad_post')? 'active' : '' ?>">
        Ad Post Setting
    </li>
</ul>