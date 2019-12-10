<div class="mp-top hidden-md-down">
    <h1 class="page_title">구독 목록</h1>
    <div class="mp_count">총 <?=$data['total']?>개</div>
    <div class="manage_top_write">
    </div>
</div>


<div class="hidden-lg-up">
    <!--작을 때-->
    <div class="form_card_wrap">
        <?php if($data['total']==0){?>
            <div class="form_empty">아직 구독하는 팀이 없습니다.</div>

        <?php }?>

        <?php foreach ($data['result'] as $result_list): ?>
            <div class="form_card">
                <div class="fc_info">
                    <div class="fci_title">
                        <a href="/@<?=$result_list['url']?>"><?=$result_list['title']?></a>
                    </div>
                    <div class="">
                       구독 날짜 <?=$result_list['crt_date']?>
                    </div>
                    <form action="/mypage/subscribe/delete" method="post">
                        <input type="hidden" name="subscribe_id" value="<?=$result_list['subscribe_id']?>">
                        <input type="submit" value="취소" class="btn btn-sm btn-outline-danger btn-cancel">
                    </form>

                </div>

            </div>

        <?php endforeach;?>

    </div>
</div>
<div class="mp_form_list hidden-md-down">
    <table class="table table-hover table-responsive-sm">
        <thead>
        <tr>
            <th>구독 번호</th>
            <th>구독하는 팀</th>
            <th>구독 날짜</th>
            <th>취소</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){
            ?>
            <tr>
                <td colspan="9" class="form_empty">아직 구독하는 팀이 없습니다.</td>
            </tr>

        <?php }?>
        <?php   $count = $data['total'];
        foreach ($data['result'] as $result):

        ?>
            <tr>
                <td><?=$count?></td>
                <td><a href="/@<?=$result['url']?>"><?=$result['title']?></a></td>
                <td><?=$result['crt_date']?></td>
                <td>
                    <form action="/mypage/subscribe/delete" method="post">
                        <input type="hidden" name="subscribe_id" value="<?=$result['subscribe_id']?>">
                        <input type="submit" value="취소" class="btn btn-sm btn-outline-danger btn-cancel">
                    </form>
                </td>

            </tr>
        <?php
            $count--;
        endforeach;?>
        </tbody>
    </table>
</div>

<nav class="page-navigation">
    <ul class="pagination justify-content-center">
        <?php echo $data['pagination'];?>
    </ul>
</nav>