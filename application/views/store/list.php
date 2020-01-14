
<div class="cont-padding">

	<div class="header_box header_space"></div>
	<h1 class="top_title">
		store
	</h1>

    <?php if(!is_null($search_query['search'])&&$search_query['search']!=''){?>

        <div class="search_top">
            <span class="search_top_icon"><i class="fas fa-search"></i></span>
            <span class="search_top_text"><?=$search_query['search']?>의 검색 결과</span>
        </div>
    <?php }?>
    <div class="sorting">
        <form action="/store/lists/1/q" method="get" class="nav-search sorting_search">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">

                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
	<div class="mp_form_list">
		<?php
         if(count($data['result'])==0){?>
            <div class="">
                아직 발행된 포스트가 없습니다.
            </div>
        <?php }else{
             foreach ($data['result'] as $result){

             $text = substr($result['contents'], 0, 1050);
             $content = strip_tags ($text);
             ?>
             <a class="store_list_item" href="/store/view/<?=$result['store_id']?>">
                 <span class="store_list_title"><?=$result['title']?></span>
                 <span class="store_list_cont"><?=str_replace("&nbsp;","",$content);?></span>
                 <span class="store_list_info">
				<span class="store_list_read_more">Read more  →</span>
				<span class="store_list_date"><i class="far fa-clock"></i> <?=substr($result['crt_date'],0,10); ?></span>
			</span>
             </a>

         <?php }

         }?>

	</div>
	<nav class="page-navigation">
		<ul class="pagination justify-content-center">
			<?php echo $data['pagination'];?>
		</ul>
	</nav>
</div>

<?php if($user['level']==9){?>
    <a href="/store/upload" class="btn btn-outline-action">등록</a>
<?php }?>
