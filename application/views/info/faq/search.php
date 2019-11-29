</div>

<div class="container-fluid cont-padding main_with_bg">
	<div class="container top_padding_sec" >
		<div class="tmm_heading1 main_hero">
			<?=$this->lang->line('faq')?>
		</div>
		<div class="faq_search hidden-sm-down">
			<form action="/info/faq/search/q?">
				<div class="input-group">
					<input class="form-control top-nav-search" type="search" placeholder="검색어를 입력해주세요." aria-label="Search" name="search"
						   value="<?= $search ?>">
					<div class="input-group-append">
						<button class="btn btn-outline-search" type="submit"><i class="fas fa-search"></i>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="container faq-padding">
    <div class="faq_wrap">
        <div class="row">
            <nav class="col-lg-2 col-md-3 d-none d-md-block sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <?php foreach ($cate_list as $cate_key => $cate_item){?>
                            <li class="faq-item">
                                <a class="nav-link faq-link faq_nav_<?=$cate_item["url_name"]?>"
                                   href="/info/faq/<?=$cate_item['url_name']?>/list"><?=$cate_item['title']?></a>
                            </li>
                        <?php }?>
                    </ul>
                </div>
            </nav>
            <main role="main" id="faq_search_main" class="col-md-9 ml-sm-auto col-lg-10">
                <div class="faq_sm_nav hidden-md-up">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <button class="faq_dropdown" type="button" id="faq_dropdown_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$cate_info['title']?></button>
                            <div class="dropdown-menu faq-dropdown-menu" aria-labelledby="faq_dropdown_btn">
                                <?php foreach ($cate_list as $cate_m_key => $cate_m_item){?>
                                    <a class="faq-dropdown-item faq_nav_<?=$cate_m_item['url_name']?>" href="/info/faq/<?=$cate_m_item['url_name']?>/list"><?=$cate_m_item["title"]?></a>
                                <?php }?>
                            </div>
                        </div>
                        <form class="col-md-6 col-sm-6 col-6" action="/info/faq/search/q?" style="padding-left: 0;">
                            <div class="input-group">
                                <input class="form-control top-nav-search" type="search" placeholder="검색어를 입력해주세요." aria-label="Search" name="search"
                                       value="<?= $search ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-search" type="submit"><i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="faq_cont" class="faq_section">

					<div class="box_title_wrap">
						<i class="sprite sprite-box"></i>
						<span class="box_title"><?=$cate_info['title']?></span>
					</div>

                    <?php
                    if($cate_cont==null){?>
                        <div class="item_empty">
                            <div class="item_empty_icon">
                                <i class="fas fa-exclamation"></i>
                            </div>
                            검색 결과가 없습니다.<br>
                            다른 검색어로 검색해보세요.
                        </div>
                    <?php }else{
                        $now_cate_id = $cate_cont[0]['category_id'];
                        foreach ($cate_cont as $r_key => $r_item){?>
                            <div class="faq_contents">
                                <!--이전이랑 다르면 -->
                                <?php if($now_cate_id!=$r_item['category_id'] || $r_key==0){
                                    $now_cate_id = $r_item['category_id']; // 현재걸로 대치
                                    ?>
                                    <h4 class="faq_sub_title"><?=$r_item['cate_title']?></h4>
                                <?php }?>

                                <div class="faq_ques">
                                    <a href="/info/faq/<?=$r_item['url_name']?>/view/<?=$r_item['order']?>" onclick="ga_send_event('faq','<?=$r_item['url_name']?>',<?=$r_item['order']?>)"><?=$r_item['order']?>. <?=$r_item['title']?></a>
                                </div>
                                <div class="faq_ans" <?php if($r_item['order']===$faq_order){echo 'style="display:block;"';}?>><?=$r_item['contents']?></div>
                            </div>
                        <?php }
                    }?>
                </div>
            </main>
        </div>
    </div>

</div>
