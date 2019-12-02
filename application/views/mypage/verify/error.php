<?php
//print_r($Res);
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-02-26
 * Time: 오후 5:45
 */?>
<div class="mp_error">
    <h5>ERROR: <?=htmlspecialchars($Res['RETURNCODE'])?></h5>
    <p><?=str_replace(".","<br>",htmlspecialchars($Res['RETURNMSG']))?></p>
    <a href="/mypage">본인인증 페이지로 돌아가기</a>
</div>
