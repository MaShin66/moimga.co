<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>

<h1 class="admin_sec_title"><a href="/admin/faq/">자주 묻는 질문 (총 <?=$data['total']?> 개)</a></h1>
<div class="admin_sort">

    <div class="btn-toolbar" role="toolbar">
        <div class="dropdown">
            <button type="button" class="btn btn-secondary  btn-sm dropdown-toggle" id="dropdownMenuLevel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo ($search_query['category']!=null) ? '카테고리: '.$cate_list[$search_query['category']-1]['name'] : '카테고리';?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLevel">
                <a class="dropdown-item" href="/admin/faq/lists/1/q?search=<?=$search_query['search']?>">전체</a>
                <div class="dropdown-divider"></div>
                <?php foreach ($cate_list as $key => $item){?>
                    <a class="dropdown-item" href="/admin/faq/lists/1/q?search=<?=$search_query['search']?>&category=<?=$item['faq_category_id']?>"><?=$item['name']?></a>
                <?php }?>
            </div>
        </div>
        <form action="/admin/faq/lists/1/q" method="get">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                <input type="hidden" name="category" value="<?=$search_query['category']?>">

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
            <th>제목</th>
            <th>카테고리</th>
            <th>날짜</th>
            <th>조회수</th>
            <th>순서</th>
            <th>수정</th>
            <th>삭제</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="8" class="form_empty">아직 자주 묻는 질문이 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['faq_id']?></td>
                <td><a href="/admin/faq/detail/<?=$result['faq_id']?>"><?=$result['title']?></td>
                <td><a href="/admin/faq/lists/1/q?category=<?=$result['faq_category_id']?>"><?=$result['name']?></a></td>

                <td><?=$result['crt_date']?></td>
                <td><?=$result['hit']?></td>
                <td><?=$result['order']?></td>

                <td>
                    <a href="/admin/faq/upload/<?=$result['faq_id']?>" class="btn btn-outline-secondary btn-sm">수정</a>
                </td>

                <td>

                    <form action="/admin/faq/delete/" method="post">
                        <input type="hidden" name="faq_id" value="<?=$result['faq_id']?>">
                        <input type="submit"  class="btn btn-outline-danger btn-delete  btn-sm" value="삭제">
                    </form>

                </td>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<hr>
    <a href="/admin/faq/upload" class="btn btn-outline-action btn-sm">등록</a>
<hr>

<nav class="page-navigation">
    <ul class="pagination justify-content-center">
        <?php echo $data['pagination'];?>
    </ul>
</nav>