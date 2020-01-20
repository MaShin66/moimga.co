
<div class="list_top">
    <h1 class="top_title">통합 검색</h1>
    <h2 class="top_desc">모임가.co 내 통합 검색</h2>
</div>

<div class="search_quick">
    <span class="sq_title">세부검색 바로가기</span>
    <ul class="sq_link">

        <li><a href="/team/lists/1/q?search=<?=$search_query['search']?>">팀</a></li>
        <li><a href="/program/lists/1/q?search=<?=$search_query['search']?>">프로그램</a></li>
        <li><a href="/after/lists/1/q?search=<?=$search_query['search']?>">후기</a></li>
        <li><a href="/contents/lists/1/q?search=<?=$search_query['search']?>">콘텐츠</a></li>
        <li><a href="/store/lists/1/q?search=<?=$search_query['search']?>">스토어</a></li>
        <li><a href="/team/blog/lists/1/q?search=<?=$search_query['search']?>">팀 블로그</a></li>

    </ul>
</div>
<div class="search_wrap">
    <h2 class="search_title">
        <span class="title_bar"></span>
        <a href="/team/lists/1/q?search=<?=$search_query['search']?>">팀</a>

        <span class="search_more">
        <a href="/team/lists/1/q?search=<?=$search_query['search']?>" >모두 보기 <i class="fas fa-chevron-right"></i></a>
    </span>

    </h2>

    <?php if(count($team_list)==0){?>
        <div class="result_empty">
            검색 결과가 없습니다.
        </div>
    <?php }else{?>

        <div class="row">
            <?php $this->load->view('team/thumbs', array('team'=>$team_list)); ?>
        </div>

    <?php }?></div>
<div class="search_wrap">

    <h2 class="search_title">
        <span class="title_bar"></span>
        <a href="/program/lists/1/q?search=<?=$search_query['search']?>">프로그램</a>

        <span class="search_more">
        <a href="/program/lists/1/q?search=<?=$search_query['search']?>" >모두 보기 <i class="fas fa-chevron-right"></i></a>
    </span>
    </h2>


    <?php if(count($program_list)==0){?>
        <div class="result_empty">
            검색 결과가 없습니다.
        </div>
    <?php }else{?>

        <div class="row">
            <?php $this->load->view('program/main_thumbs', array('program'=>$program_list)); ?>
        </div>
    <?php }?></div>
<div class="search_wrap">

    <h2 class="search_title">
        <span class="title_bar"></span>
        <a href="/after/lists/1/q?search=<?=$search_query['search']?>">후기</a>
        <span class="search_more">
        <a href="/after/lists/1/q?search=<?=$search_query['search']?>" >모두 보기 <i class="fas fa-chevron-right"></i></a>
    </span>
    </h2>

    <?php if(count($after_list)==0){?>
        <div class="result_empty">
            검색 결과가 없습니다.
        </div>
    <?php }else{?>
        <div class="row">
            <?php $this->load->view('after/main_thumbs', array('after'=>$after_list)); ?>
        </div>
    <?php }?>
</div>
<div class="search_wrap">

    <h2 class="search_title">
        <span class="title_bar"></span>
        <a href="/contents/lists/1/q?search=<?=$search_query['search']?>">콘텐츠</a>
        <span class="search_more">
        <a href="/contents/lists/1/q?search=<?=$search_query['search']?>" >모두 보기 <i class="fas fa-chevron-right"></i></a>
    </span>
    </h2>


    <?php if(count($contents_list)==0){?>
        <div class="result_empty">
            검색 결과가 없습니다.
        </div>
    <?php }else{?>

        <div class="row">

            <?php $this->load->view('contents/search_thumbs', array('contents'=>$contents_list, 'type'=>'contents')); ?>
        </div>
    <?php }?>

</div>
<div class="search_wrap">
    <h2 class="search_title">
        <span class="title_bar"></span>
        <a href="/store/lists/1/q?search=<?=$search_query['search']?>">스토어</a>
        <span class="search_more">
        <a href="/store/lists/1/q?search=<?=$search_query['search']?>" >모두 보기 <i class="fas fa-chevron-right"></i></a>
    </span>
    </h2>


    <?php if(count($store_list)==0){?>
        <div class="result_empty">
            검색 결과가 없습니다.
        </div>
    <?php }else{?>
        <div class="row">

            <?php $this->load->view('contents/search_thumbs', array('contents'=>$store_list, 'type'=>'store')); ?>
        </div>
    <?php }?>
</div>
<div class="search_wrap"><h2 class="search_title">
        <span class="title_bar"></span>
        <a href="/team/blog/lists/1/q?search=<?=$search_query['search']?>">팀 블로그</a>
        <span class="search_more">
        <a href="/team/blog/lists/1/q?search=<?=$search_query['search']?>" >모두 보기 <i class="fas fa-chevron-right"></i></a>
    </span>
    </h2>


    <?php if(count($team_blog_list)==0){?>
        <div class="result_empty">
            검색 결과가 없습니다.
        </div>
    <?php }else{?>

        <div class="row">

            <?php $this->load->view('team/blog/search_thumbs', array('team_blog'=>$team_blog_list)); ?>
        </div>
    <?php }?>
</div>
