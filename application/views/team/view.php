<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-07
 * Time: 오후 5:11
 */?>


<div class="subscribe" onclick="set_subscribe(<?=$team_info['team_id']?>)">
북마크
</div>

<h1>팀 정보</h1>
<h2><?=$team_info['title']?></h2>
<h3><?=$team_info['name']?></h3>
<div class="">
    <?=$team_info['contents']?>
</div>

<h2>프로그램</h2>
<ol>
    <?php foreach ($programs as $key=>$item){?>
        <li>  <a href="/<?=$at_url?>/program/detail/<?=$item['program_id']?>"><?=$item['title']?></a></li>
    <?php }?>
</ol>
<h2>블로그</h2>