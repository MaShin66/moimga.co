<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-10
 * Time: 오후 8:00
 */?>
<h2>지원서 정보</h2>

기본 정보가 여기잇고..

<a href="/manage/application/forms/<?=$app_info['application_id']?>">폼 목록</a>

<a href="/manage/after/detail/<?=$app_info['application_id']?>" class="btn btn-outline-secondary">후기 보기</a>


<a href="/manage/after/upload/?app_id=<?=$app_info['application_id']?>" class="btn btn-outline-secondary">후기 작성</a>

<div class="">


    <a href="/manage/deposit/lists/<?=$app_info['application_id']?>">입금 목록</a>

</div>
