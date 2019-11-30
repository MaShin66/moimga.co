<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>
<h1 class="admin_sec_title"><a href="/admin/team/">팀  (총 <?=$data['total']?> 개)</a></h1>
<div class="admin_sort">

    <div class="btn-toolbar justify-content-between" role="toolbar">
        <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
            <a href="/admin/team/lists/1/q?search=<?=$search_query['search']?>" class="btn <?php echo (is_null($search_query['status'])) ? 'btn-secondary' : 'btn-outline-secondary';?>">전체</a>
            <a href="/admin/team/lists/1/q?search=<?=$search_query['search']?>&status=on" class="btn <?php echo ($search_query['status']=='on') ? 'btn-secondary' : 'btn-outline-secondary';?>">공개</a>
            <a href="/admin/team/lists/1/q?search=<?=$search_query['search']?>&status=off" class="btn <?php echo ($search_query['status']=='off') ? 'btn-secondary' : 'btn-outline-secondary';?>">비공개</a>
        </div>
        <form action="/admin/team/lists/1/q" method="get">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                <input type="hidden" name="status" value="<?=$search_query['status']?>">

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
            <th>고유 주소</th>
            <th>상태</th>
            <th>상세</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="5" class="form_empty">아직 만든 팀이 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['team_id']?></td>
                <td><a href="/admin/team/detail/<?=$result['team_id']?>"><?=$result['title']?></a></td>
                <td><?=$result['url']?></td>
                <td><?=$this->lang->line($result['status'])?>
                    <form action="/admin/set_status/" method="post">
                        <input type="hidden" name="unique_id" value="<?=$result['team_id']?>">
                        <input type="hidden" name="type" value="team">

                        <?php if($result['status']=='on'){ ?>
                            <input type="hidden" name="status" value="off">
                            <input type="submit"  class="btn btn-outline-secondary btn-sm" value="비공개로 변경">
                        <?php }else{ ?>

                            <input type="hidden" name="status" value="on">
                            <input type="submit"  class="btn btn-outline-primary btn-sm" value="공개로 변경">

                        <?php }?>
                    </form>
                </td>
                <td><a href="/admin/team/detail/<?=$result['team_id']?>" class="btn btn-sm btn-outline-action">보기</a></td>

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