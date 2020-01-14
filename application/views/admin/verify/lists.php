<?php
?>
<div class="container" style="margin-top: 60px;">

    <h1 class="admin_sec_title"><a href="/admin/team/">본인인증 (총 <?=$data['total']?> 개)</a></h1>
    <div class="admin_sort">

        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
                <a href="/admin/verify/lists/1/q?search=<?=$search_query['search']?>" class="btn <?php echo (is_null($search_query['success'])) ? 'btn-secondary' : 'btn-outline-secondary';?>">전체</a>
                <a href="/admin/verify/lists/1/q?search=<?=$search_query['search']?>&success=1" class="btn <?php echo ($search_query['success']=='1') ? 'btn-secondary' : 'btn-outline-secondary';?>">성공</a>
                <a href="/admin/verify/lists/1/q?search=<?=$search_query['search']?>&success=0" class="btn <?php echo ($search_query['success']=='0') ? 'btn-secondary' : 'btn-outline-secondary';?>">실패</a>
            </div>
            <form action="/admin/verify/lists/1/q" method="get">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                    <input type="hidden" name="success" value="<?=$search_query['success']?>">

                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">검색</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
    <table class="table table-hover table-sm table-nowrap">
        <thead>
        <tr>
            <th scope="col">번호</th>
            <th scope="col">회원번호</th>
            <th scope="col">레벨</th>
            <th scope="col">이름</th>
            <th scope="col">생년</th>
            <th scope="col">성인</th>
            <th scope="col">성공</th>
            <th scope="col" style="width: 150px;">날짜</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($data['result'] as $result) {?>
            <tr>
                <th><?=$result['verify_id'];?></th>
                <td><a href="/admin/users/detail/<?=$result['user_id']?>"><?=$result['user_id'];?></a></td>
                <td><?=$result['level'];?></td>
                <td><a href="/admin/users/detail/<?=$result['user_id']?>"><?=$result['realname'];?></a></td>
                <td><?=$result['birth_year'];?></td>
                <td><?php if($result['adult']==1){
                        echo '성인';
                    }?></td>
                <td><?php if($result['success']==0){
                        echo '대기';
                    }?></td>
                <td><?=$result['crt_date']?></td>

            </tr>
        <?php }  ?>
        </tbody>
    </table>
    <nav class="page-navigation" style="padding-bottom: 60px;">
        <ul class="pagination">
            <?php echo $data['pagination'];?>
        </ul>
    </nav>
</div>
