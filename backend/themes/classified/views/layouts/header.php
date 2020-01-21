<header class="app-layout-header">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <button class="pull-left hidden-lg hidden-md navbar-toggle" type="button" data-toggle="layout" data-action="sidebar_toggle">
                    <span class="sr-only">Toggle drawer</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="navbar-page-title">Admin Panel</span>
            </div>
            <div class="collapse navbar-collapse" id="header-navbar-collapse">
                <!-- .navbar-left -->
                <ul class="nav navbar-nav navbar-right navbar-toolbar hidden-sm hidden-xs">

                    <!--<li>
                        <Opens the modal found at the bottom of the page -->
<!--                        <a href="javascript:void(0)" data-toggle="modal" data-target="#apps-modal"><i class="ion-grid"></i></a>
-->                  <!--  </li>-->
                    <li class="dropdown dropdown-profile">
                        <a href="#" data-toggle="dropdown">
                            <span class="m-r-sm">Demo Admin2 <span class="caret"></span></span>
                            <img class="img-avatar img-avatar-48" src="<?php echo \Yii::$app->request->BaseUrl; ?>/img/small_default_user.png" alt="Demo Admin2" />
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a data-method="post" href="<?php use yii\helpers\Url;

                                echo Url::to(['/sign-in/logout']) ?>">Sign Out</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- .navbar-right -->
            </div>
        </div>
        <!-- .container-fluid -->
    </nav>
    <!-- .navbar-default -->
</header>