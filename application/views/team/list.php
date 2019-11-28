<div class="cont-padding">
    <div class="header_box header_space"></div>
    <h1 class="top_title">팀 목록</h1>
    <?php if(!is_null($search_query['search'])){?>

        <div class="">
            <?=$search_query['search']?>의 검색 결과
        </div>
        <!---여기에 검색창도 있으면 ux 에 더 좋겟지요.. -->
    <?php }?>
<div class="prod_list">
    <div class="row">
        <?php $this->load->view('team/thumbs', array('team'=>$result['result'])); ?>
    </div>

</div>
    <nav class="page-navigation">
        <ul class="pagination justify-content-center">
            <?php echo $result['pagination'];?>
        </ul>
    </nav>
</div>