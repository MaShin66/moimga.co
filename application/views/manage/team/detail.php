<?php
/**
 */?>
<h2>팀 정보</h2>
여기에 팀 정보가..

<h3>기본 정보</h3>
<?php if($team_info['auth_code']=='1'||$team_info['auth_code']=='0'){?>

    <div class="">
        <form action="/manage/team/delete/" method="post">
            <input type="hidden" name="team_id" value="<?=$team_info['team_id']?>">
            <input type="submit"  class="btn btn-outline-danger btn-delete" value="팀 삭제">
        </form>
    </div>

<?php }?>
<div class="">
    <a href="/team/upload/<?=$team_info['team_id']?>?type=modify" class="btn btn-outline-secondary">수정</a>
</div>


<div class="">
    팀을 삭제하시면 .. 신중하게 사용해라 어ㅉㅓ구 저쩌구..
</div>

<div class="">
    상태: <?=$this->lang->line($team_info['status'])?>
</div>

<div class="">
    <form action="/manage/team/status/" method="post">
        <input type="hidden" name="team_id" value="<?=$team_info['team_id']?>">

        <?php if($team_info['status']=='on'){?>

            <input type="hidden" name="status" value="off">
            <input type="submit"  class="btn btn-outline-secondary btn-off" value="비공개로 변경">

        <?php }else if($team_info['status']=='off'){?>

            <input type="hidden" name="status" value="on">
            <input type="submit"  class="btn btn-outline-secondary btn-on" value="공개로 변경">

        <?php }?>
    </form>
</div>



<div class="">
    외부 소개 링크:

    <?php if($team_info['external_link']!=null || $team_info['external_link']!=''){?>
        링크가 없습니다.
    <?php }else{?>

        <span><?=$team_info['external_link']?></span>
        <a href="<?=$team_info['external_link']?>" target="_blank" rel="noopener" class="">이동</a>
    <?php } ?>
</div>
<h3>프로그램</h3>
<a href="/@<?=$team_info['url']?>/program/upload" class="btn btn-outline-action">프로그램 만들기</a>

<a href="/manage/program/lists/<?=$team_info['team_id']?>">프로그램 목록</a>

<ol>
<?php foreach ($program_list as $key=>$item){?>
    <li>  <a href="/manage/program/detail/<?=$item['program_id']?>"><?=$item['title']?></a></li>
<?php }?>
</ol>

<h3>팀 멤버</h3>
<a href="/manage/member/upload?team=<?=$team_info['team_id']?>" class="btn btn-outline-action">팀 멤버 지정</a>

<ol>
<a href="/manage/member/lists/<?=$team_info['team_id']?>">팀 멤버 목록</a>

    <?php if(count($member_list)==0){?>
        <div class="">팀 멤버가 없습니다.
            <a href="/manage/member/upload?team=<?=$team_info['team_id']?>" class="btn btn-outline-action">팀 멤버 지정</a>
        </div>
    <?php }else{
        foreach ($member_list as $key=>$item){?>
            <li><a href="/manage/member/detail/<?=$item['team_member_id']?>"><?=$item['realname']?></a></li>

        <?php }
    }?>

</ol>
<h3>팀 블로그</h3>
<a href="/@<?=$team_info['url']?>/blog/upload" class="btn btn-outline-action">포스트 쓰기</a>

<ol>
    <a href="/manage/blog/lists/<?=$team_info['team_id']?>">포스트 목록</a>

    <?php foreach ($blog_list as $key=>$item){?>
        <li><a href="/manage/blog/detail/<?=$item['team_blog_id']?>"><?=$item['title']?></a></li>

    <?php }?>

</ol>

<h3>구독자</h3>

<ol>
    <a href="/manage/subscribe/lists/<?=$team_info['team_id']?>">구독자 목록</a>

    <?php foreach ($subs_list as $sc_key=>$sc_item){?>
        <li><?=$sc_item['nickname']?> (<?=$sc_item['realname']?>)</li>

    <?php }?>

</ol>

<h3>사용자 후기</h3>

<ul>
    <li>이 팀에 작성된 공개된 후기만 출력됩니다.</li>

    <li>후기는 관리할 수 없습니다.</li>
    <li>후기 제목을 클릭하면 바로 후기 보기 페이지로 이동합니다.</li>
</ul>
<ol>
    <a href="/manage/after/lists/<?=$team_info['team_id']?>">후기 목록</a>

    <?php foreach ($after_list as $key=>$item){?>
        <li><a href="/after/view/<?=$item['after_id']?>" target="_blank"><?=$item['title']?></a></li>
    <?php }?>
</ol>
