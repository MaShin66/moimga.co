<!DOCTYPE html>
<html lang="en">
<head>
    <?php $location = $this->uri->segment(2);
    $section = $this->uri->segment(3);?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />

    
    <link rel="icon" href="../www/img/favicon.ico">
    <title>moimga - admin</title>
    <link rel="stylesheet" href="/www/css/bootstrap.css">
    <link rel="stylesheet" href="/www/css/overlay.css">
    <link rel="stylesheet" href="/www/css/admin.css">
    <link rel="stylesheet" href="/www/css/basic.css">
</head>
<body>
<nav class="navbar navbar-expand-md fixed-top bg-light bg-trans">
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
                    <img src="/www/img/logo.png" class="nav-logo">
                </a>
            </div>
            <div class="col top_sm_right">

                <div class="">
                    <a class="" href="/admin">ADMIN</a>
                </div>
            </div>
        </div>

    </div>

    <div class="container  hidden-sm-down">

        <a class="navbar-brand" href="/">
            Main
        </a>
        <a class="navbar-brand" href="/admin">
            Admin
        </a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto" style="font-family: 'Noto Sans KR', sans-serif;">

                <li class="nav-item dropdown">
                    <a class="nav-link " href="#" role="button" id="dropdownTeam" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        팀
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownTeam">
                        <a class="dropdown-item <?php if($location=='team'&&($section==null||$section=='list')){echo 'active';}?>" href="/admin/team">목록</a>
                        <a class="dropdown-item <?php if($location=='deleted'&&($section==null||$section=='list')){echo 'active';}?>" href="/admin/deleted">삭제됨</a>
                        <a class="dropdown-item <?php if($location=='team_blog'&&($section==null||$section=='list')){echo 'active';}?>" href="/admin/team_blog">블로그</a>
                        <a class="dropdown-item <?php if($location=='member'&&($section==null||$section=='list')){echo 'active';}?>" href="/admin/member">멤버</a>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if($location=='program'){echo 'active';}?>" href="/admin/program">프로그램</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($location=='after'){echo 'active';}?>" href="/admin/after">후기</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($location=='users'){echo 'active';}?>" href="/admin/users">회원</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($location=='faq'){echo 'active';}?>" href="/admin/faq">FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($location=='blog'){echo 'active';}?>" href="/admin/blog">블로그</a>
                </li>
            </ul>

        </div>
    </div>

    <div class="container">

        <div id="full_menu" class="overlay">


            <div class="full-item">
                <a class="full-link" href="/admin/product">상품</a>
            </div>
            <div class="full-item">
                <a class="full-link" href="/admin/demand">수요조사</a>
            </div>
            <div class="full-item">
                <a class="full-link" href="/admin/users">회원</a>
            </div>
            <div class="full-item">
                <a class="full-link" href="/admin/comment">댓글</a>
            </div>
            <div class="full-item">
                <a class="full-link" href="/admin/payment">결제</a>
            </div>
            <div class="full-item">
                <a class="full-link" href="/admin/pending">미결제</a>
            </div>
            <div class="full-item">
                <a class="full-link" href="/admin/refund_find">계좌 찾기</a>
            </div>
            <div class="full-item">
                <a class="full-link" href="/admin/fin_prod">자동 종료</a>
            </div>

        </div>
    </div>

</nav>
<div class="container">

    <div class="cont-padding">
        <?= $content_for_layout ?>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script src="/www/js/overlay.js"></script>
<script src="/www/js/admin.js"></script>
</body>
</html>
