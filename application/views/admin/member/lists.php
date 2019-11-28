<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>
<h1>팀 멤버 목록</h1>

<form action="/admin/member/lists/1/q" method="get">
    <input type="text" name="search">
    <input type="submit" value="검색">
</form>

<div class="mp_form_list hidden-md-down">
    <table class="table table-hover table-responsive-sm">
        <thead>
        <tr>
            <th>고유 번호</th>
            <th>회원 번호</th>
            <th>팀</th>
            <th>닉네임</th>
            <th>이름</th>
            <th>타입</th>
            <th>지정일</th>
            <th>삭제</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="7" class="form_empty">아직 만든 팀이 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['team_member_id']?></td>
                <td><a href="/admin/users/detail/<?=$result['user_id']?>"><?=$result['user_id']?></a></td>
                <td><a href="/admin/member/lists/1/q?team_id=<?=$result['team_id']?>"><?=$result['team_name']?></a></td>
                <td><a href="/admin/users/detail/<?=$result['user_id']?>"><?=$result['nickname']?></a></td>

                <td><?=$result['realname']?></td>
                <td>
                    <?php if($result['type']=='1'){
                        echo '대표';
                    }else{
                        echo '멤버';
                    }?>
                </td>
                <td><?=$result['crt_date']?></td>
                <td>

                    <form action="/admin/member/delete/" method="post">
                        <input type="hidden" name="team_member_id" value="<?=$result['team_member_id']?>">
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