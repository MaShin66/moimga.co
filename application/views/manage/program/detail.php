<?php
/**
 */?>
<h2>프로그램 정보</h2>
여기에 팀 정보가..

<h3>기본 정보</h3>

<?php if($program_info['position']=='representative'||$program_info['position']=='admin'){?>

    <div class="">
        <form action="/manage/program/delete/" method="post">
            <input type="hidden" name="program_id" value="<?=$program_info['program_id']?>">
            <input type="submit"  class="btn btn-outline-danger btn-delete" value="삭제">
        </form>
    </div>


<?php }?>


<div class="">
    상태: <?=$this->lang->line($program_info['status'])?>
</div>

<div class="">
    프로그램을 삭제하시면 ..
    신중하게 사용해라 어ㅉㅓ구 저쩌구..
</div>
<div class="">
    <form action="/manage/program/status/" method="post">
        <input type="hidden" name="program_id" value="<?=$program_info['program_id']?>">

        <?php if($program_info['status']=='on'){?>

            <input type="hidden" name="status" value="off">
            <input type="submit"  class="btn btn-outline-secondary btn-off" value="비공개로 변경">

        <?php }else if($program_info['status']=='off'){?>

            <input type="hidden" name="status" value="on">
            <input type="submit"  class="btn btn-outline-secondary btn-on" value="공개로 변경">

        <?php }?>
    </form>
</div>