

<h3>파트너</h3>
<a href="/manage/partner/upload?moim=<?=$moim_info['moim_id']?>">파트너 지정</a>
//파트너 목록

<?php

if($data['result']==null){ ?>
    아직 지정된 파트너가 없습니다.
<?php }else {

    foreach ($data['result'] as $key => $item) {
        ?>
        <a href="/manage/partner/detail/<?= $item['partner_id'] ?>"><?= $item['realname'] ?></a>

    <?php }
}?>

