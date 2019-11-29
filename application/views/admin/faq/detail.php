<?php
/**
 * Created by IntelliJ IDEA.
 * User: kangmin
 * Date: 2019. 11. 29.
 * Time: PM 2:07
 */?>
<h2>자주묻는 질문 보기</h2>
<h3><?=$faq_info['title']?></h3>
<div class="">
    날짜: <?=$faq_info['crt_date']?>
</div>
<div class="">
    조회수: <?=$faq_info['hit']?>
</div>
<div class="">
    <?=$faq_info['contents']?>
</div>
<hr>
<div class="">
    <a href="/admin/faq" class="btn btn-outline-primary btn-sm">목록으로</a>

</div>