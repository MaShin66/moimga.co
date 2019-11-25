<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-24
 * Time: 오후 3:39
 */?>
<h1>블로그 보기</h1>
<div class=""><?=$team_info['name']?></div>
<h2><?=$post_info['title']?></h2>
<div class="">
    <?=$post_info['contents']?>
</div>
<hr>
<div class="">
    <a href="/<?=$at_url?>/blog" class="btn-outline-primary btn">목록으로</a>
    <!--내가 글쓴이면 수정-->
    <?php if($post_info['user_id']==$user['user_id']){?>
        <a href="/<?=$at_url?>/blog/upload/<?=$post_info['team_blog_id']?>?type=modify" class="btn-outline-primary btn">수정</a>
    <?php }?>
</div>

