<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-24
 * Time: 오후 3:39
 */?>
<h1>팀 블로그</h1>
<div class="form_card_wrap">
    <?php if($data['total']==0){?>
        <div class="form_empty">아직 포스팅이 없습니다.</div>


        <?php if($as_member){?>
            <a href="/<?=$at_url?>/blog/upload" class="btn btn-outline-primary">글쓰기</a>
        <?php }?>
    <?php }?>

    <?php foreach ($data['result'] as $result_list): ?>
        <div class="form_card">
            <div class="fc_info">
                <div class="fci_title">
                    <a href="/<?=$at_url?>/blog/<?=$result_list['team_blog_id']?>"><?=$result_list['title']?></a>
                    <div class=""><?=$result_list['crt_date']?></div>
                </div>
            </div>

        </div>

    <?php endforeach;?>

</div>

    <nav class="page-navigation">
        <ul class="pagination justify-content-center">
            <?php echo $data['pagination'];?>
        </ul>
    </nav>
<?php if($as_member){?>
    <a href="/<?=$at_url?>/blog/upload" class="btn btn-outline-primary">글쓰기</a>
<?php }?>