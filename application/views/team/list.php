<?php
//sorting 시 crt_date!=null 이면 현재 뒤에 뭐가 붙었는지 가져온다
$crt_sort_txt = null;
if(!is_null($search_query['after'])){
    $crt_sort_txt = '&after='.$search_query['after'];
}else if(!is_null($search_query['heart'])){
    $crt_sort_txt = '&heart='.$search_query['heart'];
}


?>
<div class="list_top">
    <h1 class="top_title">팀</h1>
    <h2 class="top_desc">모임을 함께 만들어가는 사람들</h2>
</div>
<?php if(!is_null($search_query['search'])&&$search_query['search']!=''){?>
    <div class="search_top">
        <span class="search_top_icon"><i class="fas fa-search"></i></span>
        <span class="search_top_text"><?=$search_query['search']?>의 검색 결과</span>
    </div>
<?php }?>
<!--sorting-->
    <div class="sorting">
        <div class="btn-toolbar" role="toolbar">

            <div class="btn-group btn-group-sm mr-2" role="group" aria-label="sort group">
                <a href="/team/lists/1/q?search=<?=$search_query['search']?>&crt_date=desc<?=$crt_sort_txt?>" class="btn <?php echo ($search_query['crt_date']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">최신순</a>
                <a href="/team/lists/1/q?search=<?=$search_query['search']?>&crt_date=asc<?=$crt_sort_txt?>" class="btn <?php echo ($search_query['crt_date']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">오래된 순</a>
            </div>
            <a href="/team/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&after=desc" class="btn btn-sm mr-2 <?php echo ($search_query['after']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">후기↑</a>
            <a href="/team/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&heart=desc" class="btn btn-sm mr-2 <?php echo ($search_query['heart']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">하트↑</a>

        </div>

        <form action="/team/lists/1/q" method="get" class="nav-search sorting_search">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                <input type="hidden" name="crt_date" value="<?=$search_query['crt_date']?>">
                <input type="hidden" name="after" value="<?=$search_query['after']?>">
                <input type="hidden" name="heart" value="<?=$search_query['heart']?>">

                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
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