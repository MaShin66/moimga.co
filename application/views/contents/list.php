
<?php
//특정 카테고리 id가 있으면 상단에~~와 관련된거 ->
//저 숫자 + 로만 이루어지게 만든다..
//
?>
<div class="list_top">

    <h1 class="top_title">콘텐츠 리스트</h1>
    <h2 class="top_desc">모임에 관한, 잘 묶인 이야기들</h2>
</div>
<?php if(!is_null($search_query['search'])&&$search_query['search']!=''){?>

    <div class="">
        <?=$search_query['search']?>의 검색 결과
    </div>
    <!---여기에 검색창도 있으면 ux 에 더 좋겟지요.. -->
<?php }?>
<div class="sorting">
    <div class="btn-toolbar justify-content-between" role="toolbar">

        <form action="/contents/lists/1/q" method="get">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">

                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">검색</button>
                </div>
            </div>
        </form>
    </div>
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

             <div class="contents_wrap">
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
    <a href="/contents/upload" class="btn btn-outline-action">등록</a>
<?php }?>
