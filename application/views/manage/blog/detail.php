<?php
/**
 */?>
<h2>포스트 정보</h2>
여기에 포스트 정보가..

<h3>기본 정보</h3>

<?php if($blog_info['auth_code']=='1'||$blog_info['auth_code']=='0'){?>
    <div class="">
        <form action="/manage/blog/delete/" method="post">
            <input type="hidden" name="blog_id" value="<?=$blog_info['team_blog_id']?>">
            <input type="submit"  class="btn btn-outline-danger btn-delete" value="삭제">
        </form>
    </div>
<?php }?>
<div class="">
    포스트을 삭제하시면 ..신중하게 사용해라 어ㅉㅓ구 저쩌구..
</div>
<div class="">
    <form action="/manage/blog/status/" method="post">
        <input type="hidden" name="blog_id" value="<?=$blog_info['team_blog_id']?>">

        <?php if($blog_info['status']=='on'){?>

            <input type="hidden" name="status" value="off">
            <input type="submit"  class="btn btn-outline-secondary btn-off" value="비공개로 변경">

        <?php }else if($blog_info['status']=='off'){?>

            <input type="hidden" name="status" value="on">
            <input type="submit"  class="btn btn-outline-secondary btn-on" value="공개로 변경">

        <?php }?>
    </form>
</div>