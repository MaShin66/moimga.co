<?php
//print_r($data);
?>
<div class="mp-top hidden-md-down">
    <h1 class="page_title">포스트 목록</h1>
    <div class="mp_count">총 <?=$data['total']?>개</div>
    <div class="manage_top_write">
        <a href="/@<?=$team_info['url']?>/blog/upload" class="btn btn-sm btn-outline-action">포스트 등록</a>
    </div>
</div>


<div class="hidden-lg-up">
    <!--작을 때-->
    <div class="form_card_wrap">
        <?php if($data['total']==0){?>
            <div class="form_empty">아직 만든 포스트가 없습니다.</div>

            <div class="try_test_form">
                <a href="/@<?=$team_info['url']?>/blog/upload" class="btn btn-outline-action">포스트 등록</a>
            </div>
        <?php }?>

        <?php foreach ($data['result'] as $result_list): ?>
            <div class="form_card">
                
                <div class="fc_info">
                    <div class="fci_title">
                        <a href="/manage/blog/detail/<?=$result_list['team_blog_id']?>"><?=$result_list['title']?></a>
                    </div>
                </div>

            </div>

        <?php endforeach;?>

    </div>
</div>
<div class="mp_form_list hidden-md-down">
    <table class="table table-hover table-responsive-sm">
        <thead>
        <tr>
            <th>번호</th>
            <th>포스트 제목</th>
            <th>보기</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="2" class="form_empty">아직 만든 포스트가 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['team_blog_id']?></td>
                <td><a href="/manage/blog/detail/<?=$result['team_blog_id']?>"><?=$result['title']?></a></td>
                <td><a href="/@<?=$team_info['url']?>/blog/<?=$result['team_blog_id']?>" class="btn-outline-primary btn" >보기</a></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<nav class="page-navigation">
    <ul class="pagination justify-content-center">
        <?php echo $data['pagination'];?>
    </ul>
</nav>