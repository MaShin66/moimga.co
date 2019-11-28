<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>
<h1>삭제 목록</h1>

<form action="/admin/deleted/lists/1/q" method="get">
    <input type="text" name="search">
    <input type="submit" value="검색">
</form>

<div class="mp_form_list hidden-md-down">
    <table class="table table-hover table-responsive-sm">
        <thead>
        <tr>
            <th>번호</th>
            <th>팀 이름</th>
            <th>고유 주소</th>
            <th>상세</th>
            <th>복구</th>
            <th>완전 삭제</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="6" class="form_empty">아직 삭제된 팀이 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['team_delete_id']?></td>
                <td><a href="/admin/deleted/detail/<?=$result['team_delete_id']?>"><?=$result['title']?></a></td>
                <td><?=$result['url']?></td>
                <td><a href="/admin/deleted/detail/<?=$result['team_delete_id']?>" class="btn btn-sm btn-outline-action">보기</a></td>
                <td><?php if($result['is_recovered']=='1'){
                        echo '복구 됨';
                    }else{ ?>
                        <form action="/admin/deleted/recover/" method="post">
                            <input type="hidden" name="team_delete_id" value="<?=$result['team_delete_id']?>">
                            <input type="submit"  class="btn btn-outline-primary" value="복구">
                        </form>
                    <?php }?>
                </td>
                <td>
                    <form action="/admin/deleted/terminate/" method="post">
                        <input type="hidden" name="team_delete_id" value="<?=$result['team_delete_id']?>">
                        <input type="submit"  class="btn btn-outline-danger btn-delete" value="완전 삭제">
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