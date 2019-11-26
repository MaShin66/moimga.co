

<h3>팀 멤버 정보</h3>
<div class="">
    <div class="">
        <span>이름</span>
        <span><?=$member_info['realname']?></span>
    </div>
    <div class="">
        <span>지정 날짜</span>
        <span><?=$member_info['set_date']?></span>
    </div>
</div>
<div class="">
    <form action="/manage/member/delete/" method="post">
        <input type="hidden" name="member_id" value="<?=$member_info['team_member_id']?>">
        <input type="submit"  class="btn btn-outline-danger btn-delete" value="지정 해제">
    </form>
</div>
