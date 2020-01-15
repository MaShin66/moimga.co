<?php
/**
 * Created by PhpStorm.
  4:06
 */?>

<h1 class="admin_sec_title"><a href="/admin/main/">메인 (총 <?=$data['total']?> 개)</a></h1>

<div class="admin_list">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>고유 번호</th>
            <th>contents 1</th>
            <th>contents 2</th>
            <th>store 1</th>
            <th>store 2</th>
            <th>날짜</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="8" class="form_empty">아직 설정된 메인 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['main_id']?></td>
                <td>
                    <img src="<?=$result['contents_thumb_1']?>" class="admin_table_img">
                    <div class=""><?=$result['contents_title_1']?></div>
                    <div class=""><?=$result['contents_desc_1']?></div>
                </td>
                <td>
                    <img src="<?=$result['contents_thumb_2']?>" class="admin_table_img">
                    <div class=""><?=$result['contents_title_2']?></div>
                    <div class=""><?=$result['contents_desc_2']?></div>
                </td>
                <td>
                    <img src="<?=$result['store_thumb_1']?>" class="admin_table_img">
                    <div class=""><?=$result['store_title_1']?></div>
                    <div class=""><?=$result['store_desc_1']?></div>
                </td>
                <td>
                    <img src="<?=$result['store_thumb_2']?>" class="admin_table_img">
                    <div class=""><?=$result['store_title_2']?></div>
                    <div class=""><?=$result['store_desc_2']?></div>
                </td>
                <td><?=$result['crt_date']?></td>

                <td>
                    <a href="/admin/main/upload/<?=$result['main_id']?>" class="btn btn-outline-secondary btn-sm">수정</a>
                    <form action="/admin/main/delete/" method="post">
                        <input type="hidden" name="main_id" value="<?=$result['main_id']?>">
                        <input type="submit"  class="btn btn-outline-danger btn-delete  btn-sm" value="삭제">
                    </form>
                </td>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<hr>
    <a href="/admin/main/upload" class="btn btn-outline-action btn-sm">등록</a>
<hr>

<nav class="page-navigation">
    <ul class="pagination justify-content-center">
        <?php echo $data['pagination'];?>
    </ul>
</nav>