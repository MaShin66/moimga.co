<?php
/**
 */?>
<h2>팀 정보</h2>
여기에 팀 정보가..

<h3>기본 정보</h3>
    <a href="/manage/team/delete/<?=$team_info['team_id']?>" class="btn btn-outline-danger btn-delete">팀 삭제</a>
<div class="">
    팀을 삭제하시면 ..
    <ul>
        <li>모든 지원서 삭제</li>
        <li>사용자가 제출한 지원서 삭제</li>
    </ul>
    신중하게 사용해라 어ㅉㅓ구 저쩌구..
</div>
<h3>프로그램</h3>
<a href="/@<?=$team_info['url']?>/program/upload" class="btn btn-outline-primary">프로그램 만들기</a>

<a href="/manage/program/lists/<?=$team_info['team_id']?>">프로그램 목록</a>

<ol>
<?php foreach ($program_list as $key=>$item){?>
    <li>  <a href="/manage/program/detail/<?=$item['program_id']?>"><?=$item['title']?></a></li>
<?php }?>
</ol>

<h3>팀 멤버</h3>
<a href="/manage/member/upload?team=<?=$team_info['team_id']?>" class="btn btn-outline-primary">팀 멤버 지정</a>

<ol>
<a href="/manage/member/lists/<?=$team_info['team_id']?>">팀 멤버 목록</a>

    <?php if(count($member_list)==0){?>
        <div class="">팀 멤버가 없습니다.
            <a href="/manage/member/upload?team=<?=$team_info['team_id']?>" class="btn btn-outline-primary">팀 멤버 지정</a>
        </div>
    <?php }else{
        foreach ($member_list as $key=>$item){?>
            <li><a href="/manage/member/detail/<?=$item['member_id']?>"><?=$item['realname']?></a></li>

        <?php }
    }?>

</ol>
<h3>팀 블로그</h3>
<a href="/@<?=$team_info['url']?>/blog/upload" class="btn btn-outline-primary">포스트 쓰기</a>

<ol>
    <a href="/manage/blog/lists/<?=$team_info['team_id']?>">포스트 목록</a>

    <?php foreach ($blog_list as $key=>$item){?>
        <li><a href="/manage/blog/detail/<?=$item['team_blog_id']?>"><?=$item['title']?></a></li>

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