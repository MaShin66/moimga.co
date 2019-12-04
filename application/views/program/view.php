<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-07
 * Time: 오후 5:11
 */?>
<!--내 프로그램이면 수정 가능-->
<?php if($program_info['user_id']==$user['user_id']){?>
    <div class="">
        <a href="/manage/program/detail/<?=$program_info['program_id']?>" class="btn btn-outline-primary">관리</a>
        <a href="/@<?=$team_info['url']?>/program/upload/<?=$program_info['program_id']?>?type=modify" class="btn btn-outline-secondary">수정</a>
    </div>
<?php }?>
<h1>프로그램 보기</h1>
<h2 class=""><?=$program_info['title']?></h2>
<div class="">팀 이름: <?=$team_info['name']?></div>

<div class="btn-heart" onclick="heart('program',<?=$program_info['program_id']?>)">하트
<span id="heart_cnt"><?=$program_info['heart_count']?></span>
</div>

<h2>프로그램</h2>

<div class="">모집 인원: <?=$program_info['participant']?></div>
<div class="">가격: <?=$program_info['price']?></div>
<div class="">지역: <?=$program_info['district']?></div>
<div class="">장소: <?=$program_info['venue']?></div>
<div class="">주소: <?=$program_info['address']?></div>
<div class="">내용: <?=$program_info['contents']?></div>
<h2>행사 날짜, 시간</h2>

<ol>
    <?php foreach ($date_info as $date_key => $date_value){ ?>
        <li><?=$date_value['date']?> <?=$date_value['time']?>시</li>
    <?php }?>
</ol>


<h2>질답</h2>

<ol>
    <?php foreach ($qna_info as $akey => $avalue){ ?>
        <li><?=$avalue['question']?><br>답: <?=$avalue['answer']?></li>
    <?php }?>
</ol>

<h2>자격</h2>
<ul>
<?php foreach ($qualify_info as $qkey => $qvalue){ ?>
    <li><?=$qvalue['contents']?></li>
<?php }?>
</ul>