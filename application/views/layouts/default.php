<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $og_url = '/www/img/og_logo.jpg';
    $meta_title = 'moimga';
    ?>
    <!-- ga-->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="apple-mobile-web-app-title" content="moimga">
    <!--google 웹마스터 도구--->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-153793050-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-153793050-1');
    </script>
    <!--naver 웹마스터 도구--->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=5.0"
          name="viewport"/>
    <meta name="description" content="moimga">
    <meta name="keywords" content="moimga"/>

    <meta name="twitter:card" content="summary_large_image">

    <title><?=$meta_array['title']?></title>

    <meta name="title" content="<?=$meta_array['title']?>">
    <meta name="twitter:title" content="<?=$meta_array['title']?>">
    <meta property="og:title" content="<?=$meta_array['title']?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?=$meta_array['title']?>">
    <meta property="og:description" content="<?=$meta_array['desc']?>">
    <meta property="og:url" content="<?= current_url() ?>">

    <link rel="canonical" href="<?= current_url() ?>">

    <?php if(isset($meta_array['img'])){
        $og_url = $meta_array['img'];
    }?>

    <meta name="image" content="<?=$og_url?>">
    <meta property="og:image" content="<?=$og_url?>">
    <meta name="twitter:image" content="<?=$og_url?>">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR:200,400,600,700" rel="stylesheet">
    <!-- IE -->
    <link rel="shortcut icon" type="image/x-icon" href="/www/img/favicon.ico" />
    <!-- other browsers -->
    <link rel="icon" type="image/x-icon" href="/www/img/favicon.ico" />
    <link rel="stylesheet" href="/www/css/overlay.css">
    <link rel="stylesheet" href="/www/css/bootstrap.css">
    <link rel="stylesheet" href="/www/css/basic.css">
    <link rel="stylesheet" href="/www/css/lists.css">
    <link rel="stylesheet" href="/www/css/quill.css"> <!--wzwg--->
    <?php if($meta_array['section']=='upload'){?>
        <link rel="stylesheet" href="/www/css/asDatepicker.css">
    <?php }?>
    <link rel="stylesheet" href="/www/css/<?=$meta_array['location']?>.css">
    <link rel="stylesheet" href="/www/css/<?=$meta_array['section']?>.css">

</head>
<body>

<nav class="navbar navbar-expand-md fixed-top bg-light moimga_top">
    <div class="container hidden-md-up">

        <div class="row" style="width: 100%;">
            <div class="col top_sm_left">
                <div class="toggle-button" id="hamburger">
                    <span class="bar top"></span>
                    <span class="bar middle"></span>
                    <span class="bar bottom"></span>
                </div>
                <a class="top_sm_logo" href="/">
                    <img src="/www/img/logo.png" class="nav-logo" alt="moimga logo" >
                </a>
            </div>
            <div class="col top_sm_right ">

                <?php if($user['status']=='no'){?>

                    <div class="">
                        <a class="nav-btn-link" href="/auth/login">로그인</a>
                    </div>

                <?php }else{//로그인 후 ?>

                    <ul class="mobile_top_menu">
                        <li>
                            <a class="mtb_menu" href="/alarm">
                                <i class="fas fa-bell"></i>
                                <?php if($user['alarm']>0){?>
                                    <span class="alarm_count"><?=$user['alarm']?></span>

                                <?php }?>
                            </a>

                        </li>
                        <li><a class="mtb_menu" href="/mypage"><i class="fas fa-user"></i></a></li>

                    </ul>
                <?php }?>
            </div>
        </div>
    </div>
    <ul id="full_menu" class="overlay">


        <li class="full-item">
            <form action="/search?" method="get" class="nav-search mobile-nav-search" style="margin-top: 0">
                <div class="input-group input-group-sm ">
                    <input type="text" name="search" class="form-control" placeholder="검색">

                    <div class="input-group-append">
                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </li>

        <li class="full-item">
            <a class="full-link" href="/team">팀</a>
        </li>
        <li class="full-item">
            <a class="full-link" href="/program">프로그램</a>
        </li>
        <li class="full-item">
            <a class="full-link" href="/after">후기</a>
        </li>
        <li class="full-line"></li>
        <li class="full-item">
            <a class="full-link" href="/contents">Contents</a>
        </li>
        <li class="full-item">
            <a class="full-link" href="/store">Store</a>
        </li>


        <?php if($user['status']=='yes'){?>

            <li class="full-line"></li>
            <li class="full-item">
                <a class="full-link" href="/mypage">마이페이지</a>
            </li>

            <li class="full-item">
                <a class="full-link" href="/manage/team">관리</a>
            </li>

            <li class="full-line"></li>
            <li class="full-item">
                <a class="full-link" href="/auth/logout">로그아웃</a>
            </li>

        <?php }?>


    </ul>
    <div class="container  hidden-sm-down">

        <ul class="nav">
            <a class="navbar-brand" href="/" style="padding: 10px;  z-index: 9;">
                <img src="/www/img/logo.png" class="nav-logo" alt="moimga logo" style="margin-bottom: 3px;">
            </a>


        </ul>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link menu_padding  <?php if ($meta_array['location']== 'team'||$meta_array['location']== 'team_blog') {
                    echo 'active';
                } ?>" href="/team">팀</a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu_padding <?php if ($meta_array['location']== 'program') {
                    echo 'active';
                } ?>" href="/program" >프로그램</a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu_padding <?php if ($meta_array['location']== 'after') {
                    echo 'active';
                } ?>" href="/after" >후기</a>
            </li>

            <li class="nav-store-item ml-2">
                <a class="nav-link menu_padding  <?php if ($meta_array['location']== 'contents') {
                    echo 'active';
                } ?>" href="/contents">Contents</a>
            </li>
            <li class="nav-store-item mr-3">
                <a class="nav-link menu_padding <?php if ($meta_array['location']== 'store') {
                    echo 'active';
                } ?>" href="/store" >Store</a>
            </li>
            <li class="nav-item mr-2">
                <form action="/search?" method="get" class="nav-search">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="검색">

                        <div class="input-group-append">
                            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>

            </li>
            <?php if($user['status']=='no'){?>

                <li class="nav-item">
                    <a class="nav-btn-link" href="/auth/login">로그인</a>
                </li>
            <?php }else{//로그인 후   ?>
                <li class="nav-item">
                    <a class="nav-link menu_padding <?php if ($meta_array['location'] == 'alarm') {
                        echo 'active';
                    } ?>" href="/alarm"><i class="fas fa-bell"></i>
                        <?php if($user['alarm']>0){?>
                            <span class="alarm_count"><?=$user['alarm']?></span>

                        <?php }?>
                    </a>
                </li>
                <li class="nav-item mr-2 ml-2">
                    <a class="nav-btn-link <?php if ($meta_array['location'] == 'mypage') {
                        echo 'nav-btn-active';
                    } ?>" href="/mypage">마이페이지</a>
                </li>
                <li class="nav-item">
                    <a class="nav-btn-link <?php if ($meta_array['location'] == 'manage') {
                        echo 'nav-btn-active';
                    } ?>" href="/manage/team">관리</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu_padding" href="/auth/logout">로그아웃</a>
                </li>
            <?php }?>

        </ul>
    </div>

</nav>

<div class="container <?php if($meta_array['location']!='main') echo 'cont-padding'?>">
    <?= $content_for_layout ?>
</div>
<footer class="footer">
    <div class="container">
        <div class="footer_menu">
            <a href="/info/privacy">개인정보처리방침</a>
            <span class="footer_divide"></span>
            <a href="/info/terms">이용약관</a>
        </div>
        <div class="row">
            <div class="col-lg-10 col-md-9 col-sm-12">
                <div class="footer_info">
                    상호명: (주)백지장    사업장소재지: 서울특별시 마포구 증산로 87, 문화비축기지 상암소셜박스 | 사업자등록번호: 581-86-00949 | 통신판매업신고: 2018-서울마포-01111<br>
                    고객센터: Instagram ‘moimga.co’, 카카오톡 ‘백지장’ | 평일 10:00~19:00 (토/일/공휴일 휴무) | 이메일: hello@baek.co | 대표이사: 김차근 | 개인정보책임자: 신호태
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12">
                <div class="footer_sns_wrap">
                    <a href="" class="footer_sns"><i class="fab fa-facebook-f"></i></a>
                    <a href="" class="footer_sns"><i class="fab fa-instagram"></i></a>
                </div>

            </div>

        </div>
    </div>

</footer>

<script src="https://kit.fontawesome.com/663b869fc7.js" crossorigin="anonymous"></script>
<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

<script type="text/javascript" src="/www/js/overlay.js"></script>
<script type="text/javascript" src="/www/js/basic.js"></script>

<!--js정리-->

<?php if($meta_array['location']=='program'&&$meta_array['section']=='view'){?>
    <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=b96a3667e5757f2d026734edddb51d5e&autoload=false"></script>

<?php }?>
<?php
switch ($meta_array['location']){
    //아래 3개는 section에 따라 불러오는 js 가 다르다.
    case 'team':
    case 'team_blog':
    case 'after':
    case 'contents':
    case 'store':
    case 'program':
        switch ($meta_array['section']){
            case 'lists': ?>
    <script type="text/javascript" src="/www/js/<?=$meta_array['section']?>.js"></script>
                <?php break;
            case 'view': ?>
    <script type="text/javascript" src="/www/js/<?=$meta_array['location']?>.js"></script>
    <script type="text/javascript" src="/www/js/<?=$meta_array['section']?>.js"></script>
    <script type="text/javascript" src="/www/js/heart.js"></script> <!--after, team_blog제외-->
                <?php break;
            case 'upload':?>
    <script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
    <script type="text/javascript" src="/www/js/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="/www/js/jquery-asDatepicker.js"></script>
    <script type="text/javascript" src="/www/js/postcode.js"></script>
    <script type="text/javascript" src="/www/js/language.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script type="text/javascript" src="/www/js/upload/editor.js"></script>
    <script type="text/javascript" src="/www/js/upload/<?= $meta_array['location'] ?>.js"></script>
                <?php break;
        }
        break;
    default: // 나머지는 location ?>
        <script type="text/javascript" src="/www/js/<?=$meta_array['location']?>.js"></script>
        <?php break;
}?>

</body>
</html>
