<h1 class="admin_sec_title"><a href="/admin/shop/">샵 (총 <?=$data['total']?> 개)</a></h1>
<div class="admin_sort">

    <div class="btn-toolbar justify-content-between" role="toolbar">
        <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
            <a href="/admin/shop/lists/1/q?search=<?=$search_query['search']?>" class="btn <?php echo (is_null($search_query['status'])) ? 'btn-secondary' : 'btn-outline-secondary';?>">전체</a>
            <a href="/admin/shop/lists/1/q?search=<?=$search_query['search']?>&status=on" class="btn <?php echo ($search_query['status']=='on') ? 'btn-secondary' : 'btn-outline-secondary';?>">공개</a>
            <a href="/admin/shop/lists/1/q?search=<?=$search_query['search']?>&status=off" class="btn <?php echo ($search_query['status']=='off') ? 'btn-secondary' : 'btn-outline-secondary';?>">비공개</a>
        </div>
        <form action="/admin/shop/lists/1/q" method="get">
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
            <th>제목</th>
            <th>날짜</th>
            <th>조회수</th>
            <th>보기</th>
            <th>상태</th>
            <th>수정</th>
            <th>삭제</th>
        </tr>
        </thead>
        <tbody>

        <?php if($data['total']==0){?>
            <tr>
                <td colspan="8" class="form_empty">아직 블로그가 없습니다.</td>
            </tr>

        <?php }?>
        <?php foreach ($data['result'] as $result):?>
            <tr>
                <td><?=$result['shop_id']?></td>
                <td><a href="/shop/view/<?=$result['shop_id']?>" target="_blank"><?=$result['title']?></a></td>
                <td><?=$result['crt_date']?></td>
                <td><?=$result['hit']?></td>

                <td>
                    <a href="/shop/view/<?=$result['shop_id']?>" class="btn btn-outline-secondary btn-sm" target="_blank">보기</a>
                </td>
                <td><?=$this->lang->line($result['status'])?>
                    <form action="/admin/set_status/" method="post">
                        <input type="hidden" name="unique_id" value="<?=$result['shop_id']?>">
                        <input type="hidden" name="type" value="shop">

                        <?php if($result['status']=='on'){ ?>
                            <input type="hidden" name="status" value="off">
                            <input type="submit"  class="btn btn-outline-secondary btn-sm" value="비공개로 변경">
                        <?php }else{ ?>

                            <input type="hidden" name="status" value="on">
                            <input type="submit"  class="btn btn-outline-action btn-sm" value="공개로 변경">

                        <?php }?>
                    </form>
                </td>
                <td>
                    <a href="/shop/upload?write=modify&id=<?=$result['shop_id']?>" class="btn btn-outline-secondary btn-sm" target="_blank">수정</a>
                </td>

                <td>

                    <form action="/admin/shop/delete/" method="post">
                        <input type="hidden" name="shop_id" value="<?=$result['shop_id']?>">
                        <input type="submit"  class="btn btn-outline-danger btn-delete  btn-sm" value="삭제">
                    </form>

                </td>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<hr>
<a href="/shop/upload" class="btn btn-outline-action btn-sm" target="_blank">등록</a>
<hr>


<nav class="page-navigation">
    <ul class="pagination justify-content-center">
        <?php echo $data['pagination'];?>
    </ul>
</nav>