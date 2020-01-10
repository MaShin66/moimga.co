<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-10
 * Time: 오후 3:38
 */?>
<h2>후기 보기</h2>
입력한 후기 ..

<h3>기본 정보</h3>
    <a href="/manage/after/delete/<?=$after_info['after_id']?>" class="btn btn-outline-danger btn-delete">후기 삭제</a>
    <a href="/manage/after/upload/<?=$after_info['after_id']?>?app_id=<?=$after_info['application_id']?>" class="btn btn-outline-secondary">후기 수정</a>
<div class="">
    후기를 삭제하시면 ..
    신중하게 사용해라 어ㅉㅓ구 저쩌구..
</div>
<div class="">
    제목 <?=$after_info['title']?>
    내용 <?=$after_info['contents']?>
    만든 날짜 <?=$after_info['crt_date']?>
</div>