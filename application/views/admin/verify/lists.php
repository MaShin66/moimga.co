<?php
?>
<div class="container" style="margin-top: 60px;">

    <div class="admin_sec_title"><a href="/admin/verify"> 본인인증</a> (총 <?=$data['total']?> 개) </div>
    <div class="admin_sort">
        <div class="row">
            <div class="col-lg-9 col-sm-12">

            </div>

            <div class="col-lg-3 col-sm-12">
                <form class="" method="get" action="/admin/verify/list/1/q">
                    <div class="input-group" style="height: 34px;">
                        <input type="text" class="form-control" placeholder="검색" aria-label="검색" name="search" value="<?=$search_query['search']?>">
                        <span class="input-group-btn">
                        <button class="btn btn-secondary btn-sm" type="submit">검색</button>
                    </span>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <table class="table table-hover table-sm table-responsive-sm  table-nowrap">
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
