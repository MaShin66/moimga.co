<?php
//print_r($data['result']);

?>
<div class="list_top">

    <h1 class="top_title">[시리즈] <?=$category_info['title']?></h1>
    <h2 class="top_desc"><?=$category_info['desc']?></h2>
</div>

<div class="row justify-content-md-center">
    <div class="col-lg-8 col-md-8 col-sm-12">

        <?php if(!is_null($search_query['search'])&&$search_query['search']!=''){?>

            <div class="">
                <?=$search_query['search']?>의 검색 결과
            </div>
        <?php }?>

        <div class="sorting">
            <div class="btn-toolbar justify-content-between" role="toolbar">

                <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
                    <a href="/contents/category/1/q?category=<?=$search_query['category_id']?>&search=<?=$search_query['search']?>&crt_date=desc" class="btn <?php echo ($search_query['crt_date']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">최신순</a>
                    <a href="/contents/category/1/q?category=<?=$search_query['category_id']?>&search=<?=$search_query['search']?>&crt_date=asc" class="btn <?php echo ($search_query['crt_date']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">오래된 순</a>
                </div>

                <form action="/contents/category/1/q" method="get">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                        <input type="hidden" name="crt_date" value="<?=$search_query['crt_date']?>">
                        <input type="hidden" name="category" value="<?=$search_query['category_id']?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">검색</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="prod_list">
            <?php if(count($data['result'])==0){ ?>

                <div class="result_empty">
                    아직 시리즈가 없습니다.
                </div>
            <?php }else{ ?>
                <ol class="list_wrap">
                    <?php $this->load->view('contents/category/list_thumbs', array('contents'=>$data['result'])); ?>
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
