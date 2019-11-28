<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>
<h1>회원 목록</h1>

<form action="/admin/users/lists/1/q" method="get">
    <input type="text" name="search">
    <input type="submit" value="검색">
</form>

<div class="mp_form_list hidden-md-down">
    <table class="table table-hover table-responsive-sm">
        <thead>
        <tr>
            <th>번호</th>
            <th>이름</th>
            <th>닉네임</th>
            <th>이메일</th>
            <th>레벨</th>
            <th>가입 날짜</th>
            <th>sns</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="7" class="form_empty">아직 회원이 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['id']?></td>
                <td><a href="/admin/users/detail/<?=$result['id']?>"><?=$result['realname']?></a></td>
                <td><a href="/admin/users/detail/<?=$result['id']?>"><?=$result['nickname']?></a></td>
                <td><?=$result['email']?></td>
                <td><?=$result['level']?></td>
                <td><?=$result['created']?></td>
                <td><?=$result['sns_type']?></td>

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