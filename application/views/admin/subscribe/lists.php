<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>

<h1 class="admin_sec_title"><a href="/admin/subscribe/">구독 (총 <?=$data['total']?> 개)</a></h1>
<div class="admin_sort">

    <div class="btn-toolbar" role="toolbar">
<!--        <div class="btn-group btn-group-sm" role="group" aria-label="sort group">-->
<!--            <a href="/admin/subscribe/lists/1/q?search=--><?//=$search_query['search']?><!--" class="btn  --><?php //echo (is_null($search_query['type'])) ? 'btn-secondary' : 'btn-outline-secondary';?><!--">전체</a>-->
<!--            <a href="/admin/subscribe/lists/1/q?search=--><?//=$search_query['search']?><!--&type=1" class="btn --><?php //echo ($search_query['type']=='1') ? 'btn-secondary' : 'btn-outline-secondary';?><!--">팀장</a>-->
<!--            <a href="/admin/subscribe/lists/1/q?search=--><?//=$search_query['search']?><!--&type=2" class="btn  --><?php //echo ($search_query['type']=='2') ? 'btn-secondary' : 'btn-outline-secondary';?><!--">멤버</a>-->
<!---->
<!--        </div>-->
        <form action="/admin/subscribe/lists/1/q" method="get">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
<!--                <input type="hidden" name="type" value="--><?//=$search_query['type']?><!--">-->
                <input type="hidden" name="team_id" value="<?=$search_query['team_id']?>">

                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">검색</button>
                </div>
            </div>
        </form>

    </div>

</div>


<div class="admin_list">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>고유 번호</th>
            <th>회원 번호</th>
            <th>팀</th>
            <th>지정일</th>
            <th>취소</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="8" class="form_empty">아직 구독하는 팀이 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['subscribe_id']?></td>
                <td><a href="/admin/users/detail/<?=$result['user_id']?>"><?=$result['user_id']?></a></td>
                <td><a href="/admin/subscribe/lists/1/q?team_id=<?=$result['team_id']?>"><?=$result['team_name']?></a></td>

                <td><?=$result['crt_date']?></td>
                <td>

                    <form action="/admin/subscribe/delete/" method="post">
                        <input type="hidden" name="subscribe_id" value="<?=$result['subscribe_id']?>">
                        <input type="submit"  class="btn btn-outline-danger btn-delete  btn-sm" value="취소">
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