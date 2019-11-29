<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>
<h1>자주 묻는 질문</h1>

<form action="/admin/faq/lists/1/q" method="get">
    <input type="text" name="search">
    <input type="submit" value="검색">
</form>

<div class="mp_form_list hidden-md-down">
    <table class="table table-hover table-responsive-sm">
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
                <td colspan="7" class="form_empty">아직 자주 묻는 질문이 없습니다.</td>
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
    <a href="/admin/faq/upload" class="btn btn-outline-primary btn-sm">등록</a>
<hr>

<nav class="page-navigation">
    <ul class="pagination justify-content-center">
        <?php echo $data['pagination'];?>
    </ul>
</nav>