<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-07
 * Time: 오후 5:11
 */?>


<?php if($as_member){?>
    <div class="">
        <a href="/manage/team/detail/<?=$team_info['team_id']?>" class="btn btn-outline-primary">관리</a>
        <a href="/team/upload/<?=$team_info['team_id']?>?type=modify" class="btn btn-outline-secondary">수정</a>
    </div>
<?php }?>

<div class="subscribe" onclick="set_subscribe(<?=$team_info['team_id']?>)">
북마크
</div>

    <div class="btn-heart" onclick="heart('team',<?=$team_info['team_id']?>)">하트
        <span id="heart_cnt"><?=$team_info['heart_count']?></span>
    </div>



<h1>팀 정보</h1>
<h2><?=$team_info['title']?></h2>
<h3><?=$team_info['name']?></h3>
<div class="">
    <?=$team_info['contents']?>
</div>

<h2>프로그램</h2>

<?php if(count($programs)==0){?>
    아직 프로그램이 없습니다.
<?php }else{?>
    <ol>
        <?php foreach ($programs as $key=>$item){?>
            <li>  <a href="/<?=$at_url?>/program/<?=$item['program_id']?>"><?=$item['title']?></a></li>
        <?php }?>
    </ol>
<?php }?>


<h2>블로그</h2>

<?php if(count($team_blog)==0){?>
    아직 포스트가 없습니다.
<?php }else{?>
    <ol>
        <?php foreach ($team_blog as $b_key=>$b_item){?>
            <li>  <a href="/<?=$at_url?>/blog/<?=$b_item['team_blog_id']?>"><?=$b_item['title']?></a></li>
        <?php }?>
    </ol>
<?php }?>