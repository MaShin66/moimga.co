</div>
<div class="container-fluid main_back_color main_top">
    <div class="container">
        <div class="main_img">
            <img src="/www/img/img_1.png" class="main_top_img">
        </div>
        <h1 class="main_title">모임가: 낯선 순간을 함께하는 사람들</h1>
        <div class="main_sub_para">
            사람을 만난다는 건 한 사람의 일생을 여행하게 되는 것.<br>
            스크린에서 벗어나, 무거운 인맥에서도 벗어나 공동의 주제로 담백하게 이뤄지는 경험의 순간.<br>
            그런 모임 프로그램을 알리고 싶은 사람과 일상 속의 새로운 경험을 찾는 사람들을 연결합니다.<br>
            모임을 여는 데 필요한 정보와 리소스를 제공합니다.
        </div>
    </div>

</div>
<div class="container">
    <div class="main_point">
        <div class="container">
            <div class="row">
                <?php $this->load->view('main/thumbs', array('list'=>$main_info['contents'], 'type'=>'contents')); ?>
                <?php $this->load->view('main/thumbs', array('list'=>$main_info['store'], 'type'=>'store')); ?>
            </div>
        </div>
    </div>
    <div class="main_box main_false">

    </div>
    <div class="main_box">
        <h2 class="main_sub_title">
            <a class="main_title_link" href="/team" >팀: 모임을 만들어가는 사람들</a>
        </h2>
        <a href="/team" class="main_see_more">모두 보기</a>
        <div class="main_contents">
            <div class="row">
                <?php $this->load->view('team/thumbs', array('team'=>$team_list)); ?>
            </div>
        </div>
    </div>

    <div class="main_box">
        <h2 class="main_sub_title"><a class="main_title_link" href="/program" >프로그램: 당신이 참여할 모임들</a></h2>
        <a href="/program" class="main_see_more">모두 보기</a>
        <div class="main_contents">
            <div class="row">
                <?php $this->load->view('program/main_thumbs', array('program'=>$program_list)); ?>
            </div>
        </div>
    </div>

    <div class="main_box">
        <h2 class="main_sub_title">
            <a class="main_title_link" href="/after" >후기: 참여자들로부터 듣는 생생한 후기</a>
        </h2>
        <a href="/after" class="main_see_more">모두 보기</a>
        <div class="main_contents">
            <div class="row">

                <?php $this->load->view('after/main_thumbs', array('after'=>$after_list)); ?>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid main_back_color">
    <div class="container">
        <div class="main_img">
            <img src="/www/img/img_2.png" class="main_bottom_img">
        </div>
        <h2 class="main_title">모임가.co에 바라는 의견을 보내주세요</h2>
        <div class="main_sub_para">
            모임가.co는 모든 모임가 여러분의 더 나은 일상과 삶을 위해 만들어졌습니다.<br>
            추가되길 바라는 기능, 개선사항, 불편한 점 등을 제보해주시면 모든 메시지에 반드시 24시간 내로 회신드리겠습니다!<br>
            여러분의 의견 하나 하나가 소중하기 때문입니다.<br>
        </div>
        <div class="main_sub_para main_empha">인스타그램 계정 ‘moimga.co’로 DM을 보내주세요.</div>
    </div>
</div>