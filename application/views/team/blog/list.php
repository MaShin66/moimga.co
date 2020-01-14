

<div class="list_top">

    <h1 class="top_title">팀 <?=$team_info['name']?>의 블로그</h1>
</div>


<div class="row justify-content-md-center">
    <div class="col-lg-8 col-md-8 col-sm-12">
<?php if(!is_null($search_query['search'])&&$search_query['search']!=''){?>
    <div class="search_top">
        <span class="search_top_icon"><i class="fas fa-search"></i></span>
        <span class="search_top_text"><?=$search_query['search']?>의 검색 결과</span>
    </div>
    <!---여기에 검색창도 있으면 ux 에 더 좋겟지요.. -->
<?php }?>
    <div class="sorting">


            <form action="/team/blog/lists/1/q" method="get"  class="nav-search sorting_search">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                    <input type="hidden" name="team_id" value="<?=$search_query['team_id']?>">

                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
    </div>

    <div class="prod_list">
        <?php if($data['total']==0){ ?>

            <div class="result_empty">
                아직 포스팅이 없습니다.
            </div>
        <?php }else{ ?>
            <ol class="list_wrap">
                <?php $this->load->view('team/blog/thumbs', array('post'=>$data['result'])); ?>
            </ol>
        <?php }?>
    </div>
    <nav class="page-navigation">
        <ul class="pagination justify-content-center">
            <?php echo $data['pagination'];?>
        </ul>
    </nav>
<?php
 if($as_member&&$team_info){?>
    <a href="/@<?=$team_info['url']?>/blog/upload" class="btn btn-outline-action">글쓰기</a>
<?php }?>
    </div>
</div>
