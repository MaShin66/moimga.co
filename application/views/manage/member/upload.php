

<h1>팀 멤버 지정하기</h1>
<?php echo form_open(base_url().'manage/member/upload?team='.$team_info['team_id']);?>
팀 이름 <?=$team_info['title']?>

<p>사용자 찾기</p>
 <input type="text" class="form-control" id="email">
<!--추천은 띄워주지 않는다.. 개인정보 보호를 위해서.. 이 아이디가 있는지 없는지만 출력해줌-->
<div class="" id="email_error"></div>
<div class="" id="email_dup_error"></div>
<div class="" id="find_status">X or O</div>
<input type="hidden" name="user_id" value="" id="user_id"> <!--지정 될 팀 멤버의 user_id-->
<input type="hidden" name="team_id" value="<?=$team_info['team_id']?>" id="team_id">
<ul>
    <li>다른 팀의 팀장은 추가할 수 없습니다.</li>
    <li>이메일을 정확하게 입력해주세요.</li>
</ul>
<?php echo form_submit('submit', '지정하기', array('class'=>'btn btn-full btn-action')); ?>
<?php echo form_close()?>
