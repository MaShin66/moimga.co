<h1>블로그 목록</h1>

<form action="/admin/blog/lists/1/q" method="get">
    <input type="text" name="search">
    <input type="submit" value="검색">
</form>

<div class="mp_form_list hidden-md-down">
    <table class="table table-hover table-responsive-sm">
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
                <td><?=$result['blog_id']?></td>
                <td><a href="/blog/view/<?=$result['blog_id']?>" target="_blank"><?=$result['title']?></a></td>
                <td><?=$result['crt_date']?></td>
                <td><?=$result['hit']?></td>

                <td>
                    <a href="/blog/view/<?=$result['blog_id']?>" class="btn btn-outline-secondary btn-sm" target="_blank">보기</a>
                </td>
                <td><?=$this->lang->line($result['status'])?>
                    <form action="/admin/set_status/" method="post">
                        <input type="hidden" name="unique_id" value="<?=$result['blog_id']?>">
                        <input type="hidden" name="type" value="blog">

                        <?php if($result['status']=='on'){ ?>
                            <input type="hidden" name="status" value="off">
                            <input type="submit"  class="btn btn-outline-secondary btn-sm" value="비공개로 변경">
                        <?php }else{ ?>

                            <input type="hidden" name="status" value="on">
                            <input type="submit"  class="btn btn-outline-primary btn-sm" value="공개로 변경">

                        <?php }?>
                    </form>
                </td>
                <td>
                    <a href="/blog/upload?write=modify&id=<?=$result['blog_id']?>" class="btn btn-outline-secondary btn-sm" target="_blank">수정</a>
                </td>

                <td>

                    <form action="/admin/blog/delete/" method="post">
                        <input type="hidden" name="blog_id" value="<?=$result['blog_id']?>">
                        <input type="submit"  class="btn btn-outline-danger btn-delete  btn-sm" value="삭제">
                    </form>

                </td>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<hr>
<a href="/blog/upload" class="btn btn-outline-primary btn-sm" target="_blank">등록</a>
<hr>


<nav class="page-navigation">
    <ul class="pagination justify-content-center">
        <?php echo $data['pagination'];?>
    </ul>
</nav>