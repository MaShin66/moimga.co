<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-07
 * Time: 오후 5:11
 */?>
<h1>후기 보기</h1>
<h2><?=$after_info['title']?></h2>
<div class="">
    후기 이름: <?=$after_info['team_title']?>
</div>

<div class="">
    글쓴이 <br>
    <?=$after_info['nickname']?>
</div>
<div class="">
    날짜 <br>
    <?=$after_info['crt_date']?>
</div>

<div class="">
    내용 <br>
    <?=$after_info['contents']?>
</div>

<div class="">
    <a class="btn btn-outline-secondary" href="/<?=$this->uri->segment(1)?>">목록</a>
</div>