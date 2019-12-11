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
            <div class="form_empty">아직 구독자가 없습니다.</div>

        <?php }else{
            $order = count($data['result']);
            foreach ($data['result'] as $result_list): ?>
            <div class="form_card">
                <div class="fc_info">

                    <div class="">
                        <span>닉네임(이름)</span>
                        <span><?=$result['nickname']?> (<?=$result['realname']?>)</span>
                    </div>

                    <div class="">
                        <span>이메일</span>
                        <span><?=$result['email']?></span>
                    </div>

                    <div class="">
                        <span>구독 시작일</span>
                        <span><?=$result['set_date']?></span>
                    </div>
                </div>

            </div>

        <?php endforeach;?>
        <?php }?>
    </div>
</div>
<div class="mp_form_list hidden-md-down">
    <table class="table table-hover table-responsive-sm">
        <thead>
        <tr>
            <th>구독 순서</th>
            <th>닉네임</th>
            <th>구독자</th>
            <th>이메일</th>
            <th>구독 시작일</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="5" class="form_empty">아직 구독자가 없습니다.</td>
            </tr>

        <?php }else{

        $order = count($data['result']);
        foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$order?></td>
                <td><?=$result['nickname']?></td>
                <td><?=$result['realname']?></td>
                <td><?=$result['email']?></td>
                <td><?=$result['set_date']?></td>

            </tr>
        <?php
            $order--;
        endforeach;?>
        <?php }?>
        </tbody>
    </table>
</div>

<nav class="page-navigation">
    <ul class="pagination justify-content-center">
        <?php echo $data['pagination'];?>
    </ul>
</nav>