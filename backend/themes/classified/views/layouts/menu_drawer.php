<aside class="app-layout-drawer">

    <!-- Drawer scroll area -->
    <div class="app-layout-drawer-scroll">
        <!-- Drawer logo -->
        <div id="logo" class="drawer-header">
            <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['dashboard/index']);  ?>"">
                <?php $logo=\common\components\AppHelper::getSiteLogo();?>
                <img class="img-responsive" src="<?php echo $logo ?>" title="admin" alt="admin" /></a>
        </div>

        <!-- Drawer navigation -->
        <nav class="drawer-main">
            <ul class="nav nav-drawer">
                <li class="nav-item nav-drawer-header">Apps</li>

                <li class="nav-item">
                    <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['dashboard/index']);  ?>"><i class="ion-ios-speedometer-outline"></i> Dashboard</a>
                </li>

                <li class="nav-item nav-drawer-header">Management</li>

                <li class="nav-item nav-item-has-subnav">
                    <a href="#"><i class="ion-image"></i> Ads</a>
                    <ul class="nav nav-subnav">
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['post-ad/active']);  ?>">Active Ads</a></li>
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['post-ad/pending']);  ?>">Pending Ads</a></li>
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['post-ad/hidden']);  ?>">Hidden by user</a></li>
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['post-ad/resubmitted']);  ?>">Resubmited Ads</a></li>
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['post-ad/modification-required']);  ?>">Warned Ads</a></li>
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['post-ad/rejected']);  ?>">Rejected Ads</a></li>
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['post-ad/expire']);  ?>">Expire Ads</a></li>
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['post-ad/index']);  ?>">All Ads List</a></li>
                    </ul>
                </li>
                <li class="nav-item nav-item-has-subnav">
                    <a href="#"><i class="ion-bag"></i> Membership <span class="label label-warning">New</span></a>
                    <ul class="nav nav-subnav">
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['plans/index']);  ?>">Plan</a></li>
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['packages/index']);  ?>">Package</a></li>
                        <!--<li><a href="upgrades.html">Upgrades</a></li>
                        <li><a href="cron_logs.html">Cron Logs</a></li>
                        <li><a href="payment_methods.html">Payment Methods</a></li>-->
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['categories/index']);  ?>"><i class="ion-ios-list-outline"></i> Category</a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['custom-fields/index']);  ?>"><i class="ion-android-options"></i> Custom Fields <span class="label label-info">Unique</span></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['review/index']);  ?>"><i class="ion-android-star-half"></i> Review</a>
                </li>

                <!--<li class="nav-item">
                    <a href="themes.html"><i class="fa fa-television"></i> Change Theme</a>
                </li>-->
                <!--<li class="nav-item">
                    <a href="email-template.html"><i class="ion-ios-email"></i> Email Template <span class="label label-info">Unique</span></a>
                </li>-->
                <li class="nav-item nav-drawer-header">International</li>
                <li class="nav-item">
                    <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['languages/index']);  ?>"><i class="fa fa-language"></i> Languages <span class="label label-info">Unique</span></a>
                </li>
               <!-- <li class="nav-item">
                    <a href="currency.html"><i class="fa fa-money"></i> Currencies</a>
                </li>
                <li class="nav-item">
                    <a href="loc_countries.html"><i class="ion-ios-location-outline"></i> Countries</a>
                </li>
                <li class="nav-item">
                    <a href="timezones.html"><i class="ion-clock"></i> Time Zones</a>
                </li>-->

                <li class="nav-item nav-drawer-header">Settings</li>


                <li class="nav-item nav-item-has-subnav">
                    <a href="#"><i class="ion-android-settings"></i> Setting</a>
                    <ul class="nav nav-subnav">
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['settings/index']);  ?>">General</a></li>
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['settings/logo-watermark']);  ?>">Logo / Watermark</a></li>
                        <li><a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['settings/ad-post']);  ?>">Ad Post Setting</a></li>

                    </ul>
                </li>
                <li class="nav-item nav-drawer-header">Content</li>
                <li class="nav-item">
                    <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['content/page/index']);  ?>"><i class="ion-ios-browsers-outline"></i> Pages</a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['faqs/index']);  ?>"><i class="ion-clipboard"></i> FAQ</a>
                </li>
                <!--<li class="nav-item">
                    <a href="transactions.html"><i class="ion-arrow-graph-up-right"></i> Transactions</a>
                </li>-->
                <li class="nav-item">
                    <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['advertising/index']);  ?>"><i class="ion-ios-monitor-outline"></i> Advertising</a>
                </li>
                <li class="nav-item nav-drawer-header">Account</li>
                <li class="nav-item">
                    <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['user/index']);  ?>"><i class="ion-ios-people"></i> Users</a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo  Yii::$app->urlManager->createAbsoluteUrl(['admin-user/index']);  ?>"><i class="ion-android-contact"></i> Admin Users</a>
                </li>
                <!--<li class="nav-item">
                    <a href="update.html"><i class="ion-ios-list-outline"></i>Update <span class="label label-info">Unique</span></a>
                </li>-->
                <li class="nav-item">
                    <a href="<?php echo \yii\helpers\Url::to(['/sign-in/logout']);?>"><i class="ion-ios-people-outline"></i> Logout</a>
                </li>
            </ul>
        </nav>
        <!-- End drawer navigation -->
    </div>
    <!-- End drawer scroll area -->
</aside>