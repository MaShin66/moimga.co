<?php
$event_toggle = 'on';
if($search_query['event']=='on'){
    $event_toggle = null;
}
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-13
 * Time: 오후 4:47
 */?>

<div class="cont-padding">
    <div class="header_box header_space"></div>
    <h1 class="top_title">프로그램 목록</h1>

    <?php if(!is_null($search_query['search'])){?>

        <div class="">
            <?=$search_query['search']?>의 검색 결과
        </div>
    <?php }?>
    <div class="sorting">
        <div class="btn-toolbar justify-content-between" role="toolbar">

            <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
                <a href="/program/lists/1/q?search=<?=$search_query['search']?>&crt_date=desc&price=<?=$search_query['price']?>&event=<?=$search_query['event']?>" class="btn <?php echo ($search_query['crt_date']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">최신순</a>
                <a href="/program/lists/1/q?search=<?=$search_query['search']?>&crt_date=asc&price=<?=$search_query['price']?>&event=<?=$search_query['event']?>" class="btn <?php echo ($search_query['crt_date']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">오래된 순</a>
            </div>

            <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
                <a href="/program/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&price=desc&event=<?=$search_query['event']?>" class="btn <?php echo ($search_query['price']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">높은 가격 순</a>
                <a href="/program/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&price=asc&event=<?=$search_query['event']?>" class="btn <?php echo ($search_query['price']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">낮은 가격 순</a>
            </div>
            <a href="/program/lists/1/q?search=<?=$search_query['search']?>&crt_date=<?=$search_query['crt_date']?>&price=<?=$search_query['price']?>&event=<?=$event_toggle?>" class="btn <?php echo ($search_query['event']=='on') ? 'btn-secondary' : 'btn-outline-secondary';?>">가까운 이벤트</a>


            <form action="/program/lists/1/q" method="get">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                    <input type="hidden" name="crt_date" value="<?=$search_query['crt_date']?>">
                    <input type="hidden" name="price" value="<?=$search_query['price']?>">
                    <input type="hidden" name="event" value="<?=$search_query['event']?>">

                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">검색</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="prod_list">
        <div class="row">
            <?php $this->load->view('program/thumbs', array('program'=>$data['result'])); ?>
        </div>

    </div>
    <nav class="page-navigation">
        <ul class="pagination justify-content-center">
            <?php echo $data['pagination'];?>
        </ul>
    </nav>
</div>
