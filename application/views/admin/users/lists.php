<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>

<h1 class="admin_sec_title"><a href="/admin/users/">회원  (총 <?=$data['total']?> 개)</a></h1>
<div class="admin_sort">

    <div class="btn-toolbar " role="toolbar">
        <div class="dropdown">
            <button type="button" class="btn btn-secondary  btn-sm dropdown-toggle" id="dropdownMenuLevel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo ($search_query['level']!=null) ? '레벨: '.$search_query['level'] : '레벨';?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLevel">
                <a class="dropdown-item" href="/admin/users/lists/1/q?search=<?=$search_query['search']?>&sns_type=<?=$search_query['sns_type']?>">전체</a>
                <div class="dropdown-divider"></div>
                <?php for ($i=1; $i<=9; $i++){?>
                    <a class="dropdown-item" href="/admin/users/lists/1/q?search=<?=$search_query['search']?>&sns_type=<?=$search_query['sns_type']?>&level=<?=$i?>"><?=$i?></a>
                <?php }?>
            </div>
        </div>
        <div class="dropdown">
            <button type="button" class="btn btn-secondary  btn-sm dropdown-toggle" id="dropdownMenuSNS" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo ($search_query['sns_type']!=null) ? '가입 유형: '.$search_query['sns_type'] : '가입 유형';?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuSNS">
                <a class="dropdown-item" href="/admin/users/lists/1/q?search=<?=$search_query['search']?>&level=<?=$search_query['level']?>">전체</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/admin/users/lists/1/q?search=<?=$search_query['search']?>&level=<?=$search_query['level']?>&sns_type=email">E-mail</a>
                <a class="dropdown-item" href="/admin/users/lists/1/q?search=<?=$search_query['search']?>&level=<?=$search_query['level']?>&sns_type=kakao">카카오</a>
                <a class="dropdown-item" href="/admin/users/lists/1/q?search=<?=$search_query['search']?>&level=<?=$search_query['level']?>&sns_type=facebook">페이스북</a>
                <a class="dropdown-item" href="/admin/users/lists/1/q?search=<?=$search_query['search']?>&level=<?=$search_query['level']?>&sns_type=naver">네이버</a>
                <a class="dropdown-item" href="/admin/users/lists/1/q?search=<?=$search_query['search']?>&level=<?=$search_query['level']?>&sns_type=google">구글</a>
            </div>
        </div>
        <form action="/admin/users/lists/1/q" method="get">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                <input type="hidden" name="sns_type" value="<?=$search_query['sns_type']?>">
                <input type="hidden" name="level" value="<?=$search_query['level']?>">

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