<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>
<h1>후기 목록</h1>

<form action="/admin/after/lists/1/q" method="get">
    <input type="text" name="search">
    <input type="submit" value="검색">
</form>

<div class="mp_form_list hidden-md-down">
    <table class="table table-hover table-responsive-sm">
        <thead>
        <tr>
            <th>고유 번호</th>
            <th>팀</th>
            <th>이름</th>
            <th>날짜</th>
            <th>조회수</th>
            <th>보기</th>
            <th>상태</th>
            <th>삭제</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="8" class="form_empty">아직 후기가 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['after_id']?></td>
                <td><a href="/admin/after/lists/1/q?team_id=<?=$result['team_id']?>"><?=$result['team_name']?></a></td>
                <td><a href="/admin/users/detail/<?=$result['user_id']?>"><?=$result['nickname']?>(<?=$result['realname']?>)</a></td>
                <td><?=$result['crt_date']?></td>
                <td><?=$result['hit']?></td>
                <td><?=$this->lang->line($result['status'])?>
                    <form action="/admin/set_status/" method="post">
                        <input type="hidden" name="unique_id" value="<?=$result['after_id']?>">
                        <input type="hidden" name="type" value="after">

                        <?php if($result['status']=='on'){ ?>
                            <input type="hidden" name="status" value="off">
                            <input type="submit"  class="btn btn-outline-secondary btn-sm" value="비공개로 변경">
                        <?php }else{ ?>

                            <input type="hidden" name="status" value="on">
                            <input type="submit"  class="btn btn-outline-primary btn-sm" value="공개로 변경">

                        <?php }?>
                    </form>
                </td>
                <td>
                    <a href="/after/view/<?=$result['after_id']?>" class="btn btn-outline-secondary btn-sm" target="_blank">보기</a>
                </td>

                <td>

                    <form action="/admin/after/delete/" method="post">
                        <input type="hidden" name="after_id" value="<?=$result['after_id']?>">
                        <input type="submit"  class="btn btn-outline-danger btn-delete  btn-sm" value="삭제">
                    </form>

                </td>

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