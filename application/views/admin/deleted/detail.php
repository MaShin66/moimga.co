<?php
/**
 */?>
<h2>삭제된 팀 정보</h2>
<h3>기본 정보</h3>
<table class="table table-bordered table-hover">
    <tr>
        <th>이전 번호</th>
        <td><?=$data['org_team_id']?></td>
    </tr>
    <tr>
        <th>팀장 (회원번호)</th>
        <td><a href="/admin/users/detail/<?=$data['user_id']?>"><?=$data['user_id']?></a> </td>
    </tr>
    <tr>
        <th>팀 이름</th>
        <td><?=$data['name']?></td>
    </tr>

    <tr>
        <th>타이틀</th>
        <td><?=$data['title']?></td>
    </tr>

    <tr>
        <th>고유 url</th>
        <td><?=$data['url']?></td>
    </tr>
    <tr>
        <th>섬네일</th>
        <td><img src="<?=$data['thumb_url']?>"></td>
    </tr>
    <tr>
        <th>만든 날짜</th>
        <td><?=$data['crt_date']?></td>
    </tr>
    <tr>
        <th>삭제 날짜</th>
        <td><?=$data['delete_date']?></td>
    </tr>

</table>


<h3>글 내용</h3>
<?=$data['contents']?>
<hr>
<h3>복구 여부</h3>
<?php if($data['is_recovered']=='1'){
    echo '복구 됨';
}else{
    echo '복구 안됨';
}?>
<hr>

<div class="">
    <form action="/admin/deleted/terminate/" method="post">
        <input type="hidden" name="team_delete_id" value="<?=$data['team_delete_id']?>">
        <input type="submit"  class="btn btn-outline-danger btn-delete" value="완전 삭제">
    </form>
    <form action="/admin/deleted/recover/" method="post">
        <input type="hidden" name="team_delete_id" value="<?=$data['team_delete_id']?>">
        <input type="submit"  class="btn btn-outline-action" value="복구">
    </form>
</div>
<h4>완전 삭제</h4>
<ol>
    <li>DB에서 완전히 삭제됩니다. (관리자페이지에서도 확인할 수 없음)</li>
    <li>앞으로 복구가 불가능합니다.</li>

</ol>

<h4>복구</h4>
<ol>
    <li>복구를 하면 팀만 복구됩니다. (프로그램, 팀 멤버는 복구되지 않음)</li>
    <li>구독 수, 구독자, 조회수는 복구되지 않습니다.</li>
    <li>팀의 고유 번호가 변경됩니다. (e.g. 이전에 팀 번호가 1번이었다고 하면, 1번으로 복구되는 것이 아니라, 새로운 번호로 지정 됨)</li>
    <li>팀 고유 url을 다른 팀이 사용중이라면 랜덤 번호가 부여됩니다. (팀장이 변경해야함)</li>

</ol>

<hr>
<a href="/admin/deleted/" class="btn btn-sm btn-outline-secondary">목록으로</a>