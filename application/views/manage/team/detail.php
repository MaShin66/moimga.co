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
<a href="/program/upload?team=<?=$team_info['team_id']?>" class="btn btn-outline-primary">프로그램 만들기</a>
//프로그램 목록
<ol>
<?php foreach ($program_list as $key=>$item){?>
    <li>  <a href="/program/detail/<?=$item['program_id']?>"><?=$item['title']?></a></li>
<?php }?>
</ol>

<h3>팀 멤버</h3>
<a href="/manage/team_member/upload?team=<?=$team_info['team_id']?>">팀 멤버 지정</a>
//팀 멤버 목록

<ol>
<a href="/manage/team_member/lists/<?=$team_info['team_id']?>">팀 멤버 목록</a>

<?php foreach ($partner_list as $key=>$item){?>
    <li><a href="/manage/team_member/detail/<?=$item['team_member_id']?>"><?=$item['realname']?></a></li>

<?php }?>

</ol>