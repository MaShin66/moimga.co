

<h3>팀 멤버 정보</h3>
<div class="">
    <div class="">
        <span>이름</span>
        <span><?=$member_info['realname']?></span>
    </div>
    <div class="">
        <span>타입</span>
        <span><?=$this->lang->line('member_'.$member_info['type']);?></span>
    </div>
    <div class="">
        <span>지정 날짜</span>
        <span><?=$member_info['set_date']?></span>
    </div>
</div>
<?php if($member_info['type']=='2'){ //멤버인 경우?>
    <div class="">
        <form action="/manage/member/delete/" method="post">
            <input type="hidden" name="member_id" value="<?=$member_info['team_member_id']?>">
            <input type="submit"  class="btn btn-outline-danger btn-delete" value="지정 해제">
        </form>
    </div>
    <!--멤버인 경우-->
    <form action="/manage/member/set/" method="post">
        <input type="hidden" name="team_id" value="<?=$member_info['team_id']?>">
        <input type="hidden" name="type" value="1">
        <input type="hidden" name="member_id" value="<?=$member_info['team_member_id']?>">
        <input type="submit"  class="btn btn-outline-secondary" value="대표로 지정">
    </form>
    <?php }else if($member_info['type']=='1'){ //대표인 경우?>

    <!--대표인  경우-->
    <form action="/manage/member/set/" method="post">
        <input type="hidden" name="team_id" value="<?=$member_info['team_id']?>">
        <input type="hidden" name="type" value="2">
        <input type="hidden" name="member_id" value="<?=$member_info['team_member_id']?>">
        <input type="submit"  class="btn btn-outline-secondary" value="멤버로 지정">
    </form>
<ul class="">
    <li>대표를 팀 멤버에서 해지하고 싶은 경우, 멤버로 지정 후, 지정 해제 버튼을 눌러주세요.</li>
</ul>
<?php }?>
<div class="manage_bottom">
    <a href="/manage/member/lists/<?=$member_info['team_id']?>">목록</a>

</div>
