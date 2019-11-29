<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 4:06
 */?>
<h1>FAQ 카테고리 목록</h1>

<form action="/admin/faq_category/lists/1/q" method="get">
    <input type="text" name="search">
    <input type="submit" value="검색">
</form>

<div class="mp_form_list hidden-md-down">
    <table class="table table-hover table-responsive-sm">
        <thead>
        <tr>
            <th>고유 번호</th>
            <th>이름</th>
            <th>url</th>
            <th>순서</th>
            <th>수정</th>
            <th>삭제</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="8" class="form_empty">아직 FAQ 카테고리가 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['faq_category_id']?></td>
                <td><a href="/admin/faq/lists/1/q?category=<?=$result['faq_category_id']?>"><?=$result['name']?></a></td>

                <td><?=$result['url_name']?></td>
                <td><?=$result['order']?></td>
                <td>
                    <a href="/admin/faq_category/upload/<?=$result['faq_category_id']?>" class="btn btn-outline-secondary btn-sm">수정</a>
                </td>
                <td>
                    <form action="/admin/faq_category/delete/" method="post">
                        <input type="hidden" name="faq_category_id" value="<?=$result['faq_category_id']?>">
                        <input type="submit"  class="btn btn-outline-danger btn-delete  btn-sm" value="삭제">
                    </form>

                </td>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<a href="/admin/faq_category/upload/" class="btn btn-outline-primary btn-sm">등록</a>
<nav class="page-navigation">
    <ul class="pagination justify-content-center">
        <?php echo $data['pagination'];?>
    </ul>
</nav>