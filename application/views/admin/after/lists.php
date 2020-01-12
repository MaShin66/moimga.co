<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>

<h1 class="admin_sec_title"><a href="/admin/program/">후기 (총 <?=$data['total']?> 개)</a></h1>
<div class="admin_sort">

    <div class="btn-toolbar justify-content-between" role="toolbar">
        <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
            <a href="/admin/after/lists/1/q?search=<?=$search_query['search']?>&team_id=<?=$search_query['team_id']?>&user_id=<?=$search_query['user_id']?>" class="btn <?php echo (is_null($search_query['status'])) ? 'btn-secondary' : 'btn-outline-secondary';?>">전체</a>
            <a href="/admin/after/lists/1/q?search=<?=$search_query['search']?>&team_id=<?=$search_query['team_id']?>&user_id=<?=$search_query['user_id']?>&status=on" class="btn <?php echo ($search_query['status']=='on') ? 'btn-secondary' : 'btn-outline-secondary';?>">공개</a>
            <a href="/admin/after/lists/1/q?search=<?=$search_query['search']?>&team_id=<?=$search_query['team_id']?>&user_id=<?=$search_query['user_id']?>&status=off" class="btn <?php echo ($search_query['status']=='off') ? 'btn-secondary' : 'btn-outline-secondary';?>">비공개</a>
        </div>
        <form action="/admin/after/lists/1/q" method="get">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                <input type="hidden" name="status" value="<?=$search_query['status']?>">
                <input type="hidden" name="user_id" value="<?=$search_query['user_id']?>">
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
                            <input type="submit"  class="btn btn-outline-action btn-sm" value="공개로 변경">

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