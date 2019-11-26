

<h3>팀 멤버</h3>
<a href="/manage/member/upload?team=<?=$team_info['team_id']?>">팀 멤버 지정</a>
//팀 멤버 목록



<div class="hidden-lg-up">
    <!--작을 때-->
    <div class="form_card_wrap">
        <?php if($data['total']==0){?>
            <div class="form_empty">아직 팀 멤버가 없습니다.</div>

            <div class="try_test_form">
                <a href="/manage/member/upload?team=<?=$team_info['team_id']?>" class="btn btn-outline-action">팀 멤버 지정</a>
            </div>

        <?php }?>

        <?php foreach ($data['result'] as $result_list): ?>
            <div class="form_card">
                <div class="fc_info">
                    <div class="fci_title">
                        <a href="/manage/member/detail/<?=$result_list['team_member_id']?>"><?=$result_list['realname']?></a>

                        <span><?=$result_list['set_date']?></span>
                        <span><?=$result_list['email']?></span>
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
            <th>순서</th>
            <th>이름</th>
            <th>지정 날짜</th>
            <th>이메일</th>
            <th>상세</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="5" class="form_empty">아직 팀 멤버가 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['team_member_id']?></td>
                <td><a href="/manage/member/detail/<?=$result['team_member_id']?>"><?=$result['realname']?></a></td>
                <td><?=$result['set_date']?></td>
                <td><?=$result['email']?></td>
                <td><a href="/manage/member/detail/<?=$result['team_member_id']?>" class="btn btn-sm btn-outline-action">보기</a></td>

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
