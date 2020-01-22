<?php
$event_toggle = 'on';
$crt_sort_txt = null;
if($search_query['event']=='on'){
    $event_toggle = null;
}

if(!is_null($search_query['price'])){
    $crt_sort_txt = '&price='.$search_query['price'];
}else if(!is_null($search_query['heart'])){
    $crt_sort_txt = '&heart='.$search_query['heart'];
}
?>

<div class="list_top">

    <h1 class="top_title"><?php if($team_info){?>
            팀 <?=$team_info['name']?>의
        <?php }?> 프로그램</h1>
    <h2 class="top_desc">당신이 참여할 모임 프로그램들</h2>
</div>

<div class="row justify-content-md-center">
    <div class="col-lg-8 col-md-8 col-sm-12">

        <?php if(!is_null($search_query['search'])&&$search_query['search']!=''){?>

            <div class="search_top">
                <span class="search_top_icon"><i class="fas fa-search"></i></span>
                <span class="search_top_text"><?=$search_query['search']?>의 검색 결과</span>
            </div>

        <?php }?>

        <div class="sorting">
            <div class="btn-toolbar" role="toolbar">

                <div class="btn-group btn-group-sm mr-2" role="group" aria-label="sort group">
                    <a href="/program/lists/1/q?search=<?=$search_query['search']?>&crt_date=desc<?=$crt_sort_txt?>&event=<?=$search_query['event']?>" class="btn <?php echo ($search_query['crt_date']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">최신순</a>
                    <a href="/program/lists/1/q?search=<?=$search_query['search']?>&crt_date=asc<?=$crt_sort_txt?>&event=<?=$search_query['event']?>" class="btn <?php echo ($search_query['crt_date']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">오래된 순</a>
                </div>

                <div class="btn-group btn-group-sm mr-2" role="group" aria-label="sort group">
                    <a href="/program/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&price=desc&event=<?=$search_query['event']?>" class="btn <?php echo ($search_query['price']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">가격↑</a>
                    <a href="/program/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&price=asc&event=<?=$search_query['event']?>" class="btn <?php echo ($search_query['price']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">가격↓</a>
                </div>
                <a href="/program/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&heart=desc&event=<?=$search_query['event']?>" class="btn btn-sm mr-2 <?php echo ($search_query['heart']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">하트↑</a>
                <a href="/program/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?><?=$crt_sort_txt?>&event=<?=$event_toggle?>" class="btn btn-sm mr-2 <?php echo ($search_query['event']=='on') ? 'btn-secondary' : 'btn-outline-secondary';?>">가까운 이벤트</a>

            </div>
            <form action="/program/lists/1/q" method="get" class="nav-search sorting_search">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                    <input type="hidden" name="crt_date" value="<?=$search_query['crt_date']?>">
                    <input type="hidden" name="price" value="<?=$search_query['price']?>">
                    <input type="hidden" name="event" value="<?=$search_query['event']?>">

                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="prod_list">
            <?php if(count($data['result'])==0){ ?>

                <div class="result_empty">
                    아직 프로그램이 없습니다.
                </div>
            <?php }else{ ?>
                <ol class="list_wrap">
                    <?php $this->load->view('program/thumbs', array('program'=>$data['result'])); ?>
                </ol>
            <?php }?>

        </div>
        <nav class="page-navigation">
            <ul class="pagination justify-content-center">
                <?php echo $data['pagination'];?>
            </ul>
        </nav>

    </div>
</div>