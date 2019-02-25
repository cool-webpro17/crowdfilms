<?php

/* @var $this \yii\web\View */
/* @var $content string */
use Yii;

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
// use app\assets\AppAsset;
use yiister\gentelella\assets\Asset;

// AppAsset::register($this);
Asset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html data-wf-page="5bf540f1cadba33ac79ef224" data-wf-site="5be559b2511c0c4439ab8b99">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="start" property="og:title">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="Webflow" name="generator">
    <style>.w-embed-youtubevideo {
            width: 100%;
            position: relative;
            padding-bottom: 0px;
            padding-left: 0px;
            padding-right: 0px;
            background-image: url(https://d3e54v103j8qbb.cloudfront.net/static/youtube-placeholder.2b05e7d68d.svg);
            background-size: cover;
            background-position: 50% 50%;
        }

        .w-embed-youtubevideo:empty {
            min-height: 75px;
            padding-bottom: 56.25%;
        }</style>
    <script type="text/javascript">!function (o, c) {
            var n = c.documentElement, t = " w-mod-";
            n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch")
        }(window, document);</script>
    <link href="/images/favicon-32x32.png" rel="shortcut icon" type="image/x-icon">
    <link href="/images/favicon-256x256.png" rel="apple-touch-icon">
<!--    <link href="https://daks2k3a4ib2z.cloudfront.net/img/favicon.ico" rel="shortcut icon" type="image/x-icon">-->
<!--    <link href="https://daks2k3a4ib2z.cloudfront.net/img/webclip.png" rel="apple-touch-icon">-->
    <link href="/css/custom.css" rel="stylesheet" type="text/css">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="nav-md">
<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="/js/webflow.js" type="text/javascript"></script>
<?php $this->beginBody() ?>

<?php if (Yii::$app->session->get('admin') == 'adminUnlocked'): ?>
<div class="container body">

    <div class="main_container">

        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <a href="/" class="site_title"><i class="fa fa-user"></i> <span>Crowdfilms</span></a>
                </div>
                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        <img src="http://placehold.it/128x128" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2>Admin</h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

                <br/>

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <h3>General</h3>
                        <?=
                        \yiister\gentelella\widgets\Menu::widget(
                            [
                                "items" => [
                                    ["label" => "Pricing", "url" => ["admin/pricing"], "icon" => "usd"],
                                    ["label" => "Settings", "url" => "/admin/settings", "icon" => "gear"],
                                    ["label" => "Userlog", "url" => ["admin/userlog"], "icon" => "user"],
                                    ["label" => "Formula", "url" => ["admin/formula"], "icon" => "calculator"],
                                    ["label" => "Logout", "url" => ["admin/logout"], "icon" => "sign-out"],
                                ],
                            ]
                        )
                        ?>
                    </div>

                </div>
                <!-- /sidebar menu -->

            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                               aria-expanded="false">
                                <img src="http://placehold.it/128x128" alt="">Admin
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="/admin/pricing"> Pricing</a>
                                </li>
                                <li><a href="/admin/settings"> Settings</a>
                                </li>
                                <li><a href="/admin/userlog"> Userlog</a>
                                <li><a href="/admin/formula"> Formula</a>
                                </li>
                                <li><a href="/admin/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <?php if (isset($this->params['h1'])): ?>
                <div class="page-title">
                    <div class="title_left">
                        <h1><?= $this->params['h1'] ?></h1>
                    </div>
                    <div class="title_right">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">Go!</button>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
            <?php endif; ?>

            <?= $content ?>

            <?php if (Yii::$app->session->get('admin') == 'adminUnlocked'): ?>
        </div>
        <!-- /page content -->
        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Admin for Crowdfilms
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>

</div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>
<?php endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
