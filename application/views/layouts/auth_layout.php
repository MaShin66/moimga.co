<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-114822551-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-114822551-1');
    </script>
    <?php
    $location = $this->uri->segment(1);
    $section = $this->uri->segment(2);
    ?>
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
    <?php  switch ($section){
        case 'forgot_password':
            $meta_title = '비밀번호 찾기 - moimga';
            break;
        case 'register':
            $meta_title = '회원가입 - moimga';
            break;
        default:
            $meta_title = '로그인 - moimga';
            break;
    }?>
    <title><?=$meta_title?></title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR:400,700" rel="stylesheet">

    <link rel="stylesheet" href="/www/css/auth.css?ver=1.1">
    <link rel="stylesheet" href="/www/css/overlay.css?ver=1.1">
    <link rel="stylesheet" href="/www/css/bootstrap.css?ver=1.1">
    <link rel="stylesheet" href="/www/css/basic.css?ver=1.1">
    <link rel="stylesheet" href="/www/css/quill.css?ver=1.1">
    <link rel="stylesheet" href="/www/css/spinner.css?ver=1.1">
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

                    <img src="/www/img/logo.png" class="nav-logo" alt="moimga logo">
                </a>
            </div>
            <div class="col top_sm_right">
                <div class="">
                    <a class="" href="/auth/login"><i class="fas fa-power-off"></i></a>
                </div>
            </div>
        </div>


    </div>

    <div id="full_menu" class="overlay">
        <div class="full-item">
            <form action="/prod/search/1/q?" class="form-inline mobile_search">

                <div class="input-group">
                    <input class="form-control" type="search" placeholder="<?=$this->lang->line('search')?>" aria-label="Search" name="search" value="">
                    <input type="hidden" name="type" value="all">
                    <input type="hidden" name="crt_date" value="desc">
                    <div class="input-group-append">
                        <button class="btn btn-outline-search btn-sm" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>

            </form>
        </div>
        <div class="full-item">
            <a class="full-link" href="/prod"><?=$this->lang->line('product')?></a>
        </div>
        <div class="full-line"></div>
        <div class="full-item">
            <a class="full-link" href="/auth/login"><?=$this->lang->line('login')?></a>
        </div>

    </div>
    <div class="container  hidden-sm-down">
        <ul class="nav">
            <a class="navbar-brand" href="/" style="padding: 10px;  z-index: 9;">

                <img src="/www/img/logo.png" class="nav-logo" alt="moimga logo">
            </a>
            <li class="nav-item">
                <a class="nav-link  <?php if(($location == 'prod')&&($section != 'search'))  {
                    echo 'active';
                } ?>" href="/prod" style="padding: 0.85rem 1rem;"><?=$this->lang->line('product')?></a>
            </li>
        </ul>
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="/auth/login" style="padding: 0.85rem 1rem;"><?=$this->lang->line('login')?></a>
            </li>
            <li class="nav-item">

                <form action="/prod/search/1/q?" class="form-inline" style="margin-top: 8px;">

                    <div class="input-group">
                        <input class="form-control nav-search" type="search" placeholder="<?=$this->lang->line('search')?>" aria-label="Search" name="search" >
                        <input type="hidden" name="type" value="all">
                        <input type="hidden" name="crt_date" value="desc">
                        <div class="input-group-append">
                            <button class="btn btn-outline-search btn-sm" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                </form>
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
        <div class=" main_footer">
            <div class="row">
                <div class="col-lg-3 col-md-2 col-sm-12 footer_left">
                    <a class="footer_logo" href="/" >
                        <img src="/www/img/logo_white.png" alt="moimga logo">
                    </a>
                </div>
                <div class="col-lg-6 col-md-7 col-sm-12">
                    <a href="/info/faq" class="footer_link"><?=$this->lang->line('faq')?></a> | <a href="/info/terms" class="footer_link"><?=$this->lang->line('term')?></a> |
                    <a href="/info/privacy" class="footer_link"><?=$this->lang->line('privacy')?></a> | <a href="/info/pricing" class="footer_link"><?=$this->lang->line('refund_term')?></a>
                    <br>
                    COPYRIGHT <span class="footer_bold">moimga</span> ALL RIGHTS RESERVED
                    <div class="footer_more_link">
                        <a href="/info/why" class="footer_link"><?=$this->lang->line('about')?></a> | <a href="/blog" class="footer_link">BLOG</a>
                    </div>

                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 footer_etc">
                    <a href="mailto:admin@moimga.co" class="footer_link">admin@moimga.co</a><br>
                    <a href="https://twitter.com/@takemm_com" target="_blank" rel="noopener" class="footer_link">@takemm_com</a>


                    <div class="footer_more_link">

                        <span class="lang_choice">
                            <a href="/lang/set/ko" onclick="ga_send_event('change', 'language', 'korean');">한국어</a>
                            <span class="">·</span>
                            <a href="/lang/set/en" onclick="ga_send_event('change', 'language', 'english');">English</a>
                        </span>
                    </div>

                </div>

            </div>
            <div class="row">
                <div class="">
                    백지장 | 대표: 김차근 | 서울특별시 용산구 46-1 원효빌딩 879호 백지장 | 사업자등록번호: 424-17-00728 | 개인정보관리책임자: 김차근 | 통신판매신고번호: 2018-서울용산-1081 | 판매자 전용 전화: 070-4001-5560
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
<?php if($section=='register'){ ?>
    <script src="/www/js/auth.js"></script>
<?php } ?>
<?php if($section=='naver_login'){ ?>
    <script>
        var error_param = '<?=$this->input->get('error')?>';
    </script>
<?php } ?>

<script type="text/javascript" src="/www/js/basic.js?ver=1.1"></script>
<script type="text/javascript" src="/www/js/overlay.js?ver=1.1"></script>
<script type="text/javascript" src="/www/js/login.js?ver=1.1"></script>


</body>
</html>
