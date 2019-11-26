<?php
//print_r($data);
?>
<div class="mp-top hidden-md-down">
    <h1 class="page_title">팀 목록</h1>
    <div class="mp_count">총 <?=$data['total']?>개</div>
    <div class="manage_top_write">
        <a href="/team/upload" class="btn btn-sm btn-outline-action">팀 등록</a>
    </div>
</div>


<div class="hidden-lg-up">
    <!--작을 때-->
    <div class="form_card_wrap">
        <?php if($data['total']==0){?>
            <div class="form_empty">아직 만든 팀이 없습니다.</div>

            <div class="try_test_form">
                <a href="/team/upload" class="btn btn-outline-action">팀 등록</a>
            </div>

        <?php }?>

        <?php foreach ($data['result'] as $result_list): ?>
            <div class="form_card">
                <div class="fc_thumb">
                    <a href="/manage/team/detail/<?=$result_list['team_id']?>"><img src="<?=$result_list['thumb_url']?>" alt="<?=$result_list['title']?> 미리보기 이미지"></a>
                </div>
                <div class="fc_info">
                    <div class="fci_title">
                        <a href="/manage/team/detail/<?=$result_list['team_id']?>"><?=$result_list['title']?></a>
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
            <th><?=$this->lang->line('number')?></th>
            <th>팀 이름</th>
            <th>고유 주소</th>
            <th>권한</th>
            <th>상세</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="9" class="form_empty">아직 만든 팀이 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['team_id']?></td>
                <td><a href="/manage/team/detail/<?=$result['team_id']?>"><?=$result['title']?></a></td>
                <td><?=$result['url']?></td>
                <td><?=$this->lang->line($result['position'])?></td>
                <td><a href="/manage/team/detail/<?=$result['team_id']?>" class="btn btn-sm btn-outline-action">보기</a></td>

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