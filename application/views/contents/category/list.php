<?php
$list_txt = '시리즈';
if($meta_array['location']=='store'){

    $list_txt = '셀렉션';
}
//print_r($data['result']);

?>
<div class="list_top">

    <h1 class="top_title">[<?=$list_txt?>] <?=$category_info['title']?></h1>
    <h2 class="top_desc"><?=$category_info['desc']?></h2>
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

                <div class="btn-group btn-group-sm mr-2 " role="group" aria-label="sort group">
                    <a href="/<?=$meta_array['location']?>/category/1/q?category=<?=$search_query['category_id']?>&search=<?=$search_query['search']?>&crt_date=desc" class="btn <?php echo ($search_query['crt_date']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">최신순</a>
                    <a href="/<?=$meta_array['location']?>/category/1/q?category=<?=$search_query['category_id']?>&search=<?=$search_query['search']?>&crt_date=asc" class="btn <?php echo ($search_query['crt_date']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">오래된 순</a>
                </div>
            </div>
            <form action="/<?=$meta_array['location']?>/category/1/q" method="get" class="nav-search sorting_search">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                    <input type="hidden" name="crt_date" value="<?=$search_query['crt_date']?>">
                    <input type="hidden" name="category" value="<?=$search_query['category_id']?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
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
