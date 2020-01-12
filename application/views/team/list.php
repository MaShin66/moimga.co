<?php
//sorting 시 crt_date!=null 이면 현재 뒤에 뭐가 붙었는지 가져온다

$crt_sort_txt = null;
if(!is_null($search_query['after'])){
    $crt_sort_txt = '&after='.$search_query['after'];
}else if(!is_null($search_query['subscribe'])){

    $crt_sort_txt = '&subscribe='.$search_query['subscribe'];
}

?>
<div class="list_top">

    <h1 class="top_title">팀</h1>
    <h2 class="top_desc">모임을 함께 만들어가는 사람들</h2>
</div>
    <?php if(!is_null($search_query['search'])&&$search_query['search']!=''){?>

        <div class="">
            <?=$search_query['search']?>의 검색 결과
        </div>
        <!---여기에 검색창도 있으면 ux 에 더 좋겟지요.. -->
    <?php }?>
<!--sorting-->
    <div class="sorting">
        <div class="btn-toolbar justify-content-between" role="toolbar">

            <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
                <a href="/team/lists/1/q?search=<?=$search_query['search']?>&crt_date=desc<?=$crt_sort_txt?>" class="btn <?php echo ($search_query['crt_date']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">최신순</a>
                <a href="/team/lists/1/q?search=<?=$search_query['search']?>&crt_date=asc<?=$crt_sort_txt?>" class="btn <?php echo ($search_query['crt_date']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">오래된 순</a>
            </div>

            <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
                <a href="/team/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&after=desc" class="btn <?php echo ($search_query['after']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">후기 많은 순</a>
                <a href="/team/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&after=asc" class="btn <?php echo ($search_query['after']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">후기 적은 순</a>
            </div>
            <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
                <a href="/team/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&subscribe=desc" class="btn <?php echo ($search_query['subscribe']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">구독 많은 순</a>
                <a href="/team/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&subscribe=asc" class="btn <?php echo ($search_query['subscribe']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">구독 적은 순</a>
            </div>

            <form action="/team/lists/1/q" method="get">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                    <input type="hidden" name="crt_date" value="<?=$search_query['crt_date']?>">
                    <input type="hidden" name="after" value="<?=$search_query['after']?>">
                    <input type="hidden" name="subscribe" value="<?=$search_query['subscribe']?>">

                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">검색</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<div class="prod_list">
    <?php if(count($result['result'])==0){ ?>

        <div class="result_empty">
            아직 팀이 없습니다.
        </div>
    <?php }else{ ?>
        <div class="row">
            <?php $this->load->view('team/thumbs', array('team'=>$result['result'])); ?>
        </div>

    <?php }?>

</div>
    <nav class="page-navigation">
        <ul class="pagination justify-content-center">
            <?php echo $result['pagination'];?>
        </ul>
    </nav>