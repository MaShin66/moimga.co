
<?php
//특정 카테고리 id가 있으면 상단에~~와 관련된거 ->
//저 숫자 + 로만 이루어지게 만든다..
//
?>
<div class="list_top">
    <h1 class="top_title"><?=$this->lang->line($meta_array['location'])?> 리스트</h1>
    <?php   if($meta_array['location']=='contents'){?>

        <h2 class="top_desc">모임에 관한, 잘 묶인 이야기들</h2>

    <?php }?>

</div>
<?php if(!is_null($search_query['search'])&&$search_query['search']!=''){?>

    <div class="search_top">
        <span class="search_top_icon"><i class="fas fa-search"></i></span>
        <span class="search_top_text"><?=$search_query['search']?>의 검색 결과</span>
    </div>

<?php }?>
<div class="sorting">
    <form action="/<?=$meta_array['location']?>/lists/1/q" method="get" class="nav-search sorting_search">
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
//                print_r($data['result']);
             ?>

             <div class="row">

                 <?php $this->load->view('contents/thumbs', array('contents'=>$data['result'])); ?>

             </div>

         <?php

         }?>

	</div>
	<nav class="page-navigation">
		<ul class="pagination justify-content-center">
			<?php echo $data['pagination'];?>
		</ul>
	</nav>

<?php if($user['level']==9){?>
    <a href="/<?=$meta_array['location']?>/upload" class="btn btn-outline-action">등록</a>
<?php }?>
