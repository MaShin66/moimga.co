<div class="cont-padding">
    <div class="header_box header_space"></div>
    <h1 class="top_title">후기</h1>
    <?php if(!is_null($search_query['search'])&&$search_query['search']!=''){?>

        <div class="">
            <?=$search_query['search']?>의 검색 결과
        </div>
    <?php }?>
    <div class="sorting">
        <div class="btn-toolbar justify-content-between" role="toolbar">

            <div class="btn-group btn-group-sm" role="group" aria-label="sort group">
                <a href="/after/lists/1/q?search=<?=$search_query['search']?>&crt_date=desc" class="btn <?php echo ($search_query['crt_date']=='desc') ? 'btn-secondary' : 'btn-outline-secondary';?>">최신순</a>
                <a href="/after/lists/1/q?search=<?=$search_query['search']?>&crt_date=asc" class="btn <?php echo ($search_query['crt_date']=='asc') ? 'btn-secondary' : 'btn-outline-secondary';?>">오래된 순</a>
            </div>

            <form action="/after/lists/1/q" method="get">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="검색어를 입력해주세요"  value="<?=$search_query['search']?>">
                    <input type="hidden" name="crt_date" value="<?=$search_query['crt_date']?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">검색</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
<div class="prod_list">
    <div class="row">
        <?php $this->load->view('after/thumbs', array('after'=>$result['result'])); ?>
    </div>

</div>

    <nav class="page-navigation">
        <ul class="pagination justify-content-center">
            <?php echo $result['pagination'];?>
        </ul>
    </nav>
</div>
<?php if($user['status']==='yes'){?>
    <div class="">
        <a href="<?=$this->uri->segment(1)?>/upload" class="btn btn-primary">쓰기 </a>
    </div>

<?php } ?>