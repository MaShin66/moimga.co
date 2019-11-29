<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-02-15
 * Time: 오전 11:48
 */?>
<div class="info_top">
    <h1 class="info_title">자주 묻는 질문</h1>
</div>

<div class="info_wrap">
    <div class="row">
        <div class="col-lg-9 col-md-6 col-sm-12"></div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <form class="" name="search" method="get" action="/info/faq/lists/1/q">
                <div class="input-group">

                    <input type="text" class="form-control" name="search" placeholder="검색어 입력">
                    <input type="hidden" value="<?=$search_query['crt_date']?>" name="crt_date">
                    <input type="hidden" value="<?=$search_query['category']?>" name="category">
                    <button type="submit" class="btn btn-outline-action">검색</button>
                </div>
            </form>
        </div>
    </div>

</div>
<div class="faq_wrap">
    <?php if(!empty($data['result'])){
          foreach ($data['result'] as $result_list):?>

    <div class="faq_item" id="faq_<?=$result_list['faq_id']?>">
        <div class="faq_title" >
            <a href="/info/faq/view/"
            <?=$result_list['title']?></div>

    </div>
    <?php endforeach;
    }else{?>

        <div class="empty_wrap">
            <div class="empty_icon"><i class="fas fa-exclamation"></i></div>
            <span>검색 결과가 없습니다.</span>
            <div class="faq_sub_desc">다른 검색어로 검색해보세요.</div>
        </div>
    <?php }?>

</div>
<nav class="page-navigation">
    <ul class="pagination justify-content-center">
        <?php echo $data['pagination'];?>
    </ul>
</nav>