<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-27
 * Time: 오후 2:59
 */?>
<h1>통합 검색</h1>
<h2><a href="/team/lists/1/q?search=<?=$search_query['search']?>">팀</a></h2>

<?php if(count($team_list)==0){?>
    검색 결과가 없습니다.
<?php }else{?>


    <ul>
        <?php foreach ($team_list as $t_key=>$t_item){?>
            <li><?=$t_item['title']?></li>
        <?php }?>
    </ul>

<?php }?>

<h2><a href="/program/lists/1/q?search=<?=$search_query['search']?>">프로그램</a></h2>

<?php if(count($program_list)==0){?>
    검색 결과가 없습니다.
<?php }else{?>

    <ul>
        <?php foreach ($program_list as $p_key=>$p_item){?>
            <li><?=$p_item['title']?></li>
        <?php }?>
    </ul>

<?php }?>


<h2><a href="/after/lists/1/q?search=<?=$search_query['search']?>">후기</a></h2>


<?php if(count($after_list)==0){?>
    검색 결과가 없습니다.
<?php }else{?>

    <ul>
        <?php foreach ($after_list as $a_key=>$a_item){?>
            <li><?=$a_item['title']?></li>
        <?php }?>
    </ul>
<?php }?>
