<?php
$event_toggle = 'on';
if($search_query['event']=='on'){
    $event_toggle = null;
}
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>

<h1 class="admin_sec_title"><a href="/admin/program/">프로그램  (총 <?=$data['total']?> 개)</a></h1>
<div class="admin_sort">

    <div class="btn-toolbar justify-content-between" role="toolbar">
        <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
            <a href="/admin/program/lists/1/q?search=<?=$search_query['search']?>&team_id=<?=$search_query['team_id']?>" class="btn <?php echo (is_null($search_query['status'])) ? 'btn-secondary' : 'btn-outline-secondary';?>">전체</a>
            <a href="/admin/program/lists/1/q?search=<?=$search_query['search']?>&team_id=<?=$search_query['team_id']?>&status=on" class="btn <?php echo ($search_query['status']=='on') ? 'btn-secondary' : 'btn-outline-secondary';?>">공개</a>
            <a href="/admin/program/lists/1/q?search=<?=$search_query['search']?>&team_id=<?=$search_query['team_id']?>&status=off" class="btn <?php echo ($search_query['status']=='off') ? 'btn-secondary' : 'btn-outline-secondary';?>">비공개</a>
        </div>

        <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
            <a href="/admin/program/lists/1/q?search=<?=$search_query['search']?>&team_id=<?=$search_query['team_id']?>&status=<?=$search_query['status']?>&crt_date=<?=$search_query['crt_date']?>&price=desc&event=<?=$search_query['event']?>" class="btn <?php echo ($search_query['price']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">높은 가격 순</a>
            <a href="/admin/program/lists/1/q?search=<?=$search_query['search']?>&team_id=<?=$search_query['team_id']?>&status=<?=$search_query['status']?>&crt_date=<?=$search_query['crt_date']?>&price=asc&event=<?=$search_query['event']?>" class="btn <?php echo ($search_query['price']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">낮은 가격 순</a>
        </div>
        <a href="/admin/program/lists/1/q?search=<?=$search_query['search']?>&team_id=<?=$search_query['team_id']?>&status=<?=$search_query['status']?>&crt_date=<?=$search_query['crt_date']?>&price=<?=$search_query['price']?>&event=<?=$event_toggle?>" class="btn <?php echo ($search_query['event']=='on') ? 'btn-secondary' : 'btn-outline-secondary';?>">가까운 이벤트</a>


        <form action="/admin/program/lists/1/q" method="get">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                <input type="hidden" name="status" value="<?=$search_query['status']?>">
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
            <th>번호</th>
            <th>팀 이름</th>
            <th>제목</th>
            <th>보기</th>
            <th>상태</th>
            <th>삭제</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="6" class="form_empty">아직 프로그램이 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['program_id']?></td>
                <td><a href="/admin/program/lists/1/q?team_id=<?=$result['team_id']?>"><?=$result['team_name']?></a></td>
                <td><a href="/admin/program/detail/<?=$result['program_id']?>"><?=$result['title']?></a></td>
                <td><a href="/admin/program/detail/<?=$result['program_id']?>" class="btn btn-sm btn-outline-action">보기</a></td>
                <td><?=$this->lang->line($result['status'])?>
                    <form action="/admin/set_status/" method="post">
                        <input type="hidden" name="unique_id" value="<?=$result['program_id']?>">
                        <input type="hidden" name="type" value="program">

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
                    <form action="/admin/program/delete/" method="post">
                        <input type="hidden" name="program_id" value="<?=$result['program_id']?>">
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