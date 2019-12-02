<div class="mp-top hidden-md-down">
    <h1 class="page_title">후기 목록</h1>
    <div class="mp_count">총 <?=$data['total']?>개</div>
    <div class="manage_top_write">
    </div>
</div>


<div class="hidden-lg-up">
    <!--작을 때-->
    <div class="form_card_wrap">
        <?php if($data['total']==0){?>
            <div class="form_empty">아직 남긴 후기가 없습니다.</div>

        <?php }?>

        <?php foreach ($data['result'] as $result_list): ?>
            <div class="form_card">
                <div class="fc_info">
                    <div class="fci_title">
                        <a href="/mypage/after/detail/<?=$result_list['after_id']?>"><?=$result_list['title']?></a>
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
            <th>후기 번호</th>
            <th>후기 이름</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="9" class="form_empty">아직 남긴 후기가 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['after_id']?></td>
                <td><a href="/mypage/after/detail/<?=$result['after_id']?>"><?=$result['title']?></a></td>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<a href="/after/upload" class="btn btn-outline-secondary">후기 등록</a>
<nav class="page-navigation">
    <ul class="pagination justify-content-center">
        <?php echo $data['pagination'];?>
    </ul>
</nav>