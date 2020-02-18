<!DOCTYPE html>
<html lang="en">
<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-85534776-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-85534776-2');
    </script>
    <meta name="google-site-verification" content="HPAFve_ihMdRMey6ZqxxPNC4CRleSf_LT6xFaWF7cLU" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="google-signin-client_id" content="1016198125121-22ul2njjml8e8jum51ntspr2bb5a0an0.apps.googleusercontent.com">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta name="image" content="https://moimga.co/www/img/og_logo.jpg">
    <meta property="og:image" content="https://moimga.co/www/img/og_logo.jpg">
    <meta name="twitter:image" content="https://moimga.co/www/img/og_logo.jpg">
    <!-- IE -->
    <link rel="shortcut icon" type="image/x-icon" href="/www/img/favicon.ico" />
    <!-- other browsers -->
    <link rel="icon" type="image/x-icon" href="/www/img/favicon.ico" />

    <meta name="twitter:card" content="summary_large_image">

    <title><?=$meta_array['title']?></title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR:400,700" rel="stylesheet">

    <link rel="stylesheet" href="/www/css/auth.css">
    <link rel="stylesheet" href="/www/css/overlay.css">
    <link rel="stylesheet" href="/www/css/bootstrap.css">
    <link rel="stylesheet" href="/www/css/basic.css">
    <link rel="stylesheet" href="/www/css/spinner.css">
</head>
<body class="auth_back">

<div id="fb-root"></div>

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '895120010883967',
            cookie     : true,
            xfbml      : true,
            version    : 'v5.0'
        });

        FB.AppEvents.logPageView();

    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

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
                <div class="">
                    <a class="nav-btn-link" href="/auth/login">로그인</a>
                </div>
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


    </ul>
    <div class="container  hidden-sm-down">

        <ul class="nav">
            <a class="navbar-brand" href="/" style="padding: 10px;  z-index: 9;">
                <img src="/www/img/logo.png" class="nav-logo" alt="moimga logo" style="margin-bottom: 3px;" >
            </a>


        </ul>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link menu_padding  <?php if ($meta_array['location']== 'team') {
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

            <li class="nav-item">
                <a class="nav-btn-link" href="/auth/login">로그인</a>
            </li>

        </ul>
    </div>

</nav>
<div class="container container-top">

    <div class="row justify-content-md-center">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <?= $content_for_layout ?>
        </div>
    </div>

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

<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js" integrity="sha384-kW+oWsYx3YpxvjtZjFXqazFpA7UP/MbiY4jvs+RWZo2+N94PFZ36T6TFkc9O3qoB" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://static.nid.naver.com/js/naveridlogin_js_sdk_2.0.0.js" charset="utf-8"></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://apis.google.com/js/api:client.js"></script>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>

<?php if($meta_array['section']=='register'){ ?>
    <script src="/www/js/register.js"></script>
<?php } ?>
<script type="text/javascript" src="/www/js/basic.js"></script>
<script type="text/javascript" src="/www/js/overlay.js"></script>

<?php if($meta_array['section']=='login'){ ?>
    <script type="text/javascript" src="/www/js/login.js"></script>
<?php } ?>


</body>
</html>
