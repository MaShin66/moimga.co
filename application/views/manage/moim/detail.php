<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-10
 * Time: 오후 3:38
 */?>
<h2>모임 정보</h2>
여기에 모임 정보가..

<h3>기본 정보</h3>
    <a href="/manage/moim/delete/<?=$moim_info['moim_id']?>" class="btn btn-outline-danger btn-delete">모임 삭제</a>
<div class="">
    모임을 삭제하시면 ..
    <ul>
        <li>모든 지원서 삭제</li>
        <li>사용자가 제출한 지원서 삭제</li>
    </ul>
    신중하게 사용해라 어ㅉㅓ구 저쩌구..
</div>
<h3>지원서</h3>
<a href="/manage/application/upload?moim=<?=$moim_info['moim_id']?>">지원서 만들기</a>
//지원서 목록
<?php foreach ($app_list as $key=>$item){?>
    <a href="/manage/application/detail/<?=$item['application_id']?>"><?=$item['title']?></a>


<?php }?>