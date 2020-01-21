<?php
$this->title = 'Dashboard';

$js="
$(function()
    {
       // Init page helpers (Slick Slider plugin)
        App.initHelpers('slick');
    });

";
$this->registerJs($js,\yii\web\VIEW::POS_END);
?>
<!-- Stats -->
<div class="row">
    <div class="col-sm-6 col-lg-3">
        <a class="card" href="#">
            <div class="card-block clearfix">
                <div class="pull-right">
                    <p class="h6 text-muted m-t-0 m-b-xs">Active Ads</p>
                    <p class="h3 text-blue m-t-sm m-b-0">1338</p>
                </div>
                <div class="pull-left m-r">
                    <span class="img-avatar img-avatar-48 bg-blue bg-inverse"><i class="ion-ios-bell fa-1-5x"></i></span>
                </div>
            </div>
        </a>
    </div>
    <!-- .col-sm-6 -->

    <div class="col-sm-6 col-lg-3">
        <a class="card bg-green bg-inverse" href="#">
            <div class="card-block clearfix">
                <div class="pull-right">
                    <p class="h6 text-muted m-t-0 m-b-xs">New Ads</p>
                    <p class="h3 m-t-sm m-b-0">5 New</p>
                </div>
                <div class="pull-left m-r">
                    <span class="img-avatar img-avatar-48 bg-gray-light-o"><i class="ion-ios-information fa-1-5x"></i></span>
                </div>
            </div>
        </a>
    </div>
    <!-- .col-sm-6 -->
    <div class="col-sm-6 col-lg-3">
        <a class="card bg-purple bg-inverse" href="#">
            <div class="card-block clearfix">
                <div class="pull-right">
                    <p class="h6 text-muted m-t-0 m-b-xs">Total Users</p>
                    <p class="h3 m-t-sm m-b-0">3479</p>
                </div>
                <div class="pull-left m-r">
                    <span class="img-avatar img-avatar-48 bg-gray-light-o"><i class="ion-ios-people fa-1-5x"></i></span>
                </div>
            </div>
        </a>
    </div>
    <!-- .col-sm-6 -->

    <div class="col-sm-6 col-lg-3">
        <a class="card bg-blue bg-inverse" href="#">
            <div class="card-block clearfix">
                <div class="pull-right">
                    <p class="h6 text-muted m-t-0 m-b-xs">Total Income</p>
                    <p class="h3 m-t-sm m-b-0">3869.00 &#8377;</p>
                </div>
                <div class="pull-left m-r">
                    <span class="img-avatar img-avatar-48 bg-gray-light-o"><i class="ion-cash fa-1-5x"></i></span>
                </div>
            </div>
        </a>
    </div>
    <!-- .col-sm-6 -->


</div>
<!-- .row -->
<!-- End stats -->

<div class="row">
    <div class="col-lg-8">
        <!-- Transactions history Widget -->
        <div class="card">
            <div class="card-header">
                <h4>Posts Statistics</h4>
            </div>
            <div class="card-block">
                <div style="height: 238px;"><canvas class="js-chartjs-lines4"></canvas></div>
            </div>
        </div>
        <!-- .card -->
        <!-- End Transactions history Widget -->
    </div>
    <!-- .col-lg-8 -->

    <div class="col-lg-4">
        <!-- Weekly users Widget -->
        <div class="card">
            <div class="card-header">
                <h4>Weekly users </h4>
            </div>
            <div class="card-block">
                <div style="height: 238px;"><canvas class="js-chartjs-bars3"></canvas></div>
            </div>
        </div>
        <!-- .card -->
        <!-- End Weekly users Widget -->
    </div>
    <!-- .col-lg-4 -->
</div>
<!-- .row -->

<!-- .row -->
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h4>Recent 5 Ads</h4>
            </div>
            <div class="card-block">
                <div class="pro-list">
                    <div class="pro-img p-r-10">
                        <a href="#">
                            <img src="assets/images/1569301541_reviews.jpg" alt="Urgent Loan Offer To Increase Your Credit Score" style="width:75px; height: 75px;">
                        </a>
                    </div>
                    <div class="pro-detail">
                        <h5 class="m-t-0 m-b-5">
                            <a href="post_detail.html?id=5387">Urgent Loan Offer To Increase Your Credit Score</a>
                        </h5>
                        <p class="text-muted font-12">10 hours ago </p>
                    </div>
                </div>

                <div class="pro-list">
                    <div class="pro-img p-r-10">
                        <a href="#">
                            <img src="assets/images/1569301541_reviews.jpg" alt="URGENT LOAN OFFER TO SOLVE YOUR FINANCIAL ISSUE" style="width:75px; height: 75px;">
                        </a>
                    </div>
                    <div class="pro-detail">
                        <h5 class="m-t-0 m-b-5">
                            <a href="post_detail.html?id=5386">URGENT LOAN OFFER TO SOLVE YOUR FINANCIAL ISSUE</a>
                        </h5>
                        <p class="text-muted font-12">10 hours ago </p>
                    </div>
                </div>

                <div class="pro-list">
                    <div class="pro-img p-r-10">
                        <a href="#">
                            <img src="assets/images/1569301541_reviews.jpg" alt="Buy Mescaline Powder Online|buy XTC pills (Orange dom perignon XTC PILL 220 MG)|buy pure Cocaine online" style="width:75px; height: 75px;">
                        </a>
                    </div>
                    <div class="pro-detail">
                        <h5 class="m-t-0 m-b-5">
                            <a href="post_detail.html?id=5385">Buy Mescaline Powder Online|buy XTC pills (Orange dom perignon XTC PILL 220 MG)|buy pure Cocaine online</a>
                        </h5>
                        <p class="text-muted font-12">13 hours ago </p>
                    </div>
                </div>

                <div class="pro-list">
                    <div class="pro-img p-r-10">
                        <a href="#">
                            <img src="assets/images/1569301541_reviews.jpg" alt=" Buy heroin pure online | Buy Furanyl fentanyl Powder online" style="width:75px; height: 75px;">
                        </a>
                    </div>
                    <div class="pro-detail">
                        <h5 class="m-t-0 m-b-5">
                            <a href="post_detail.html?id=5384"> Buy heroin pure online | Buy Furanyl fentanyl Powder online</a>
                        </h5>
                        <p class="text-muted font-12">13 hours ago </p>
                    </div>
                </div>

                <div class="pro-list">
                    <div class="pro-img p-r-10">
                        <a href="#">
                            <img src="assets/images/1569301541_reviews.jpg" alt="What is Cosmetic Bonding?" style="width:75px; height: 75px;">
                        </a>
                    </div>
                    <div class="pro-detail">
                        <h5 class="m-t-0 m-b-5">
                            <a href="post_detail.html?id=5383">What is Cosmetic Bonding?</a>
                        </h5>
                        <p class="text-muted font-12">16 hours ago </p>
                    </div>
                </div>


                <div class="text-right">
                    <a href="posts.html" class="btn btn-sm btn-rounded btn-info m-t-10">View All</a>
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Recent Registered </h4>
            </div>
            <div class="card-block">
                <div class="table-responsive">
                    <table class="table ">
                        <thead>
                        <tr>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>DATE</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="txt-oflo">Shilpa</td>
                            <td><span class="label label-info label-rounded">octalinkskerala@gmail.com</span> </td>
                            <td class="txt-oflo">7 minutes ago</td>
                        </tr>
                        <tr>
                            <td class="txt-oflo">watcharin</td>
                            <td><span class="label label-info label-rounded">watcharin8886@gmail.com</span> </td>
                            <td class="txt-oflo">8 hours ago</td>
                        </tr>
                        <tr>
                            <td class="txt-oflo">Gerald</td>
                            <td><span class="label label-info label-rounded">mrgeraldooo@gmail.com</span> </td>
                            <td class="txt-oflo">8 hours ago</td>
                        </tr>
                        <tr>
                            <td class="txt-oflo">Adam Aliu</td>
                            <td><span class="label label-info label-rounded">adamfinancialloanservicess@gmail.com</span> </td>
                            <td class="txt-oflo">10 hours ago</td>
                        </tr>
                        <tr>
                            <td class="txt-oflo">Biznes</td>
                            <td><span class="label label-info label-rounded">aazetech@gmail.com</span> </td>
                            <td class="txt-oflo">12 hours ago</td>
                        </tr>


                        </tbody>
                    </table>
                    <a href="users.html">Check all Users</a>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- /.row -->