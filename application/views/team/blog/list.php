<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-24
 * Time: 오후 3:39
 */?>
    <h1 class="top_title">
        팀<?php if($team_info){?>
             <?=$team_info['name']?>의
        <?php }?> 블로그</h1>

<?php if(!is_null($search_query['search'])&&$search_query['search']!=''){?>

    <div class="">
        <?=$search_query['search']?>의 검색 결과
    </div>
    <!---여기에 검색창도 있으면 ux 에 더 좋겟지요.. -->
<?php }?>
    <div class="sorting">
        <div class="btn-toolbar justify-content-between" role="toolbar">


            <form action="/team/blog/lists/1/q" method="get">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                    <input type="hidden" name="team_id" value="<?=$search_query['team_id']?>">

                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">검색</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<div class="form_card_wrap">
    <?php if($data['total']==0){?>
        <div class="form_empty">아직 포스팅이 없습니다.</div>
    <?php }?>

    <?php foreach ($data['result'] as $result_list): ?>
        <div class="form_card">
            <div class="fc_info">
                <div class="fci_title">
                    <a href="/@<?=$result_list['url']?>/blog/<?=$result_list['team_blog_id']?>"><?=$result_list['title']?></a>
                    <div class=""><?=$result_list['crt_date']?></div>
                </div>
            </div>

        </div>

    <?php endforeach;?>

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