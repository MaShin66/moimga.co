<?php
/**
 * Created by IntelliJ IDEA.
 * User: kangmin
 * Date: 2019. 11. 26.
 * Time: PM 2:59
 */?>
<h1>내 후기 > 후기 관리</h1>
<h2>기본 정보</h2>
<a href="/after/view/<?=$after_info['after_id']?>"><?=$after_info['title']?></a>

<div class="">
    상태: <?=$this->lang->line($after_info['status'])?>
</div>
<div class="">
    <form action="/mypage/after/status/" method="post">
        <input type="hidden" name="after_id" value="<?=$after_info['after_id']?>">

        <?php if($after_info['status']=='on'){?>

            <input type="hidden" name="status" value="off">
            <input type="submit"  class="btn btn-outline-secondary btn-off" value="비공개로 변경">

        <?php }else if($after_info['status']=='off'){?>

            <input type="hidden" name="status" value="on">
            <input type="submit"  class="btn btn-outline-secondary btn-on" value="공개로 변경">

        <?php }?>
    </form>
</div>


<a href="/after/upload/<?=$after_info['after_id']?>?type=modify" class="btn btn-outline-secondary">수정</a>
<div class="">
    <form action="/mypage/after/delete/" method="post">
        <input type="hidden" name="after_id" value="<?=$after_info['after_id']?>">
        <input type="submit"  class="btn btn-outline-danger btn-delete" value="삭제">
    </form>
</div>


