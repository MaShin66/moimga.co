<?php
/**
 */?>
<h2>팀 포스트 정보</h2>
<h3>기본 정보</h3>
<table class="table table-bordered table-hover">
    <tr>
        <th>포스트 번호</th>
        <td><?=$data['team_blog_id']?></td>
    </tr>
    <tr>
        <th>글쓴 사람</th>
        <td><a href="/admin/users/detail/<?=$data['user_id']?>"><?=$data['user_id']?></a> </td>
    </tr>

    <tr>
        <th>팀</th>
        <td><?=$team_info['name']?><br>

            <a href="/admin/team/detail/<?=$data['team_id']?>" target="_blank">(관리자페이지)</a> | <a href="/@<?=$team_info['url']?>" target="_blank">(팀 페이지)</a>
        </td>
    </tr>

    <tr>
        <th>타이틀</th>
        <td><a href="/@<?=$team_info['url']?>/blog/<?=$data['team_blog_id']?>"><?=$data['title']?></a></td>
    </tr>

    <tr>
        <th>조회수</th>
        <td><?=$data['hit']?></td>
    </tr>
    <tr>
        <th>상태</th>
        <td><?=$this->lang->line($data['status'])?>
            <div class="">
                <form action="/admin/set_status/" method="post">
                    <input type="hidden" name="unique_id" value="<?=$data['team_blog_id']?>">
                    <input type="hidden" name="type" value="team_blog">

                    <?php if($data['status']=='on'){?>

                        <input type="hidden" name="status" value="off">
                        <input type="submit"  class="btn btn-outline-secondary btn-off" value="비공개로 변경">

                    <?php }else if($data['status']=='off'){?>

                        <input type="hidden" name="status" value="on">
                        <input type="submit"  class="btn btn-outline-secondary btn-on" value="공개로 변경">

                    <?php }?>
                </form>
            </div>
        </td>
    </tr>
    <tr>
        <th>글 쓴 날짜</th>
        <td><?=$data['crt_date']?></td>
    </tr>

</table>


<h3>내용</h3>
<?=$data['contents']?>
<hr>

<div class="">
    <form action="/admin/team_blog/delete/" method="post">
        <input type="hidden" name="team_blog_id" value="<?=$data['team_blog_id']?>">
        <input type="submit"  class="btn btn-outline-danger btn-delete" value="삭제">
    </form>
</div>

<h2>삭제</h2>
<ol>
    <li>DB에서 완전히 삭제됩니다. (관리자페이지에서도 확인할 수 없음)</li>
    <li>앞으로 복구가 불가능합니다.</li>

</ol>

<hr>
<a href="/admin/team_blog/" class="btn btn-sm btn-outline-secondary">목록으로</a>