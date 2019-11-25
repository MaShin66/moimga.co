<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $meta_title = 'moimga';

    $location = $this->uri->segment(1);
    $section = $this->uri->segment(2);
    $detail = $this->uri->segment(3);?>
    <!-- ga-->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="apple-mobile-web-app-title" content="moimga">
    <!--google 웹마스터 도구--->
    <!--naver 웹마스터 도구--->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=5.0"
          name="viewport"/>
    <meta name="description" content="moimga">
    <meta name="keywords" content="moimga"/>

    <meta name="twitter:card" content="summary_large_image">

    <title><?= $meta_title ?></title>
    <meta name="title" content="<?= $meta_title ?>">
    <meta name="twitter:title" content="<?= $meta_title ?>">
    <meta property="og:title" content="<?= $meta_title ?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $meta_title ?>">
    <meta property="og:description" content="moimga">
    <meta property="og:url" content="<?= current_url() ?>">

    <link rel="canonical" href="<?= current_url() ?>">


    <meta name="image" content="http://moimga.co/www/img/og_logo.jpg">
    <meta property="og:image" content="http://moimga.co/www/img/og_logo.jpg">
    <meta name="twitter:image" content="http://moimga.co/www/img/og_logo.jpg">
    <!-- IE -->
    <link rel="shortcut icon" type="image/x-icon" href="/www/img/favicon.ico" />
    <!-- other browsers -->
    <link rel="icon" type="image/x-icon" href="/www/img/favicon.ico" />
    <link rel="stylesheet" href="/www/css/overlay.css">
    <link rel="stylesheet" href="/www/css/bootstrap.css">
    <link rel="stylesheet" href="/www/css/basic.css">
    <link rel="stylesheet" href="/www/css/quill.css"> <!--wzwg--->
    <?php if($section=='upload'||$detail=='upload'){?>
        <link rel="stylesheet" href="/www/css/asDatepicker.css">
    <?php }?>

</head>
<body>

<nav class="navbar navbar-expand-md fixed-top bg-light">
    <div class="container hidden-md-up">

        <div class="row" style="width: 100%;">
            <div class="col top_sm_left">
                <div class="toggle-button" id="hamburger">
                    <span class="bar top"></span>
                    <span class="bar middle"></span>
                    <span class="bar bottom"></span>
                </div>
            </div>
            <div class="col top_sm_center">
                <a class="top_sm_logo" href="/">
                    <img src="/www/img/logo.png" class="nav-logo" alt="moimga logo" >
                </a>
            </div>
            <div class="col top_sm_right">

                <?php if($user['status']=='no'){?>

                    <div class="">
                        <a class="" href="/login">로그인</a>
                    </div>

                <?php }else{//로그인 후 ?>
                    <div class="">
                        <a class="" href="/mypage">마이페이지</a>
                    </div>

                    <div class="">
                        <a class="" href="/manage">관리</a>
                    </div>

                    <div class="">
                        <a class="" href="/logout">로그아웃</a>
                    </div>

                <?php }?>
            </div>
        </div>
    </div>
    <div id="full_menu" class="overlay">
        <div class="full-item">
            <a class="full-link" href="/team">팀</a>
        </div>

        <div class="full-line"></div>
        <div class="full-item">
            <a class="full-link" href="/mypage">메뉴2</a>
        </div>

    </div>
    <div class="container  hidden-sm-down">

        <ul class="nav">
            <a class="navbar-brand" href="/" style="padding: 10px;  z-index: 9;">
                <img src="/www/img/logo.png" class="nav-logo" alt="moimga logo" >
            </a>
            <li class="nav-item">
                <a class="nav-link  <?php if ($location== 'team') {
                    echo 'active';
                } ?>" href="/team" style="padding: 0.85rem 1rem;">팀</a>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?php if ($location== 'program') {
                    echo 'active';
                } ?>" href="/program" style="padding: 0.85rem 1rem;">프로그램</a>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?php if ($location== 'after') {
                    echo 'active';
                } ?>" href="/after" style="padding: 0.85rem 1rem;">후기</a>
            </li>
        </ul>
        <ul class="nav">
            <?php if($user['status']=='no'){?>
                <li class="nav-item">
                    <a class="nav-link alarm-nav <?php if ($location == 'auth') {
                        echo 'active';
                    } ?>" href="/auth">로그인</a>
                </li>
            <?php }else{//로그인 후 ?>
                <li class="nav-item">
                    <a class="nav-link alarm-nav <?php if ($location == 'mypage') {
                        echo 'active';
                    } ?>" href="/mypage">마이페이지</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link alarm-nav <?php if ($location == 'manage') {
                        echo 'active';
                    } ?>" href="/manage">관리</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link alarm-nav" href="/auth/logout">로그아웃</a>
                </li>
            <?php }?>

        </ul>
    </div>

</nav>

<div class="container cont-padding">
    <?= $content_for_layout ?>
</div>
<footer class="footer">
    <div class="container">
        <div class=" main_footer">
            
        </div>
    </div>

</footer>
<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

<?php if($section=='upload'||$detail=='upload'){?>

    <script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
    <script type="text/javascript" src="/www/js/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="/www/js/jquery-asDatepicker.js"></script>
    <script type="text/javascript" src="/www/js/postcode.js"></script>
    <script type="text/javascript" src="/www/js/language.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script type="text/javascript" src="/www/js/editor.js"></script>
<?php }?>
<?php if($section=='blog'||$detail=='upload'){?>
    <script type="text/javascript" src="/www/js/team_blog.js"></script>
<?php }?>
<script type="text/javascript" src="/www/js/overlay.js"></script>
<script type="text/javascript" src="/www/js/basic.js"></script>

<?php if($section!='program'&&$section!='team '){?>
    <script type="text/javascript" src="/www/js/<?= $location ?>.js"></script>
<?php }else{?>
    <script type="text/javascript" src="/www/js/<?= $section ?>.js"></script>
    <script type="text/javascript" src="/www/js/subscribe.js"></script> <!--section==team 일 경우에 유용-->

<?php }?>
<?php if($location=='manage'){?>
    <script type="text/javascript" src="/www/js/<?= $section ?>.js"></script>
<?php }?>
</body>
</html>
