<div class="cont-padding">
    <div class="header_box header_space"></div>
    <h1 class="top_title">후기 목록</h1>
<div class="prod_list">
    <div class="row">
        <?php $this->load->view('after/thumbs', array('after'=>$result['result'])); ?>
    </div>

</div>

    <div class="">
        <a href="<?=$this->uri->segment(1)?>/write" class="btn btn-primary">쓰기 </a>
    </div>
    <nav class="page-navigation">
        <ul class="pagination justify-content-center">
            <?php echo $result['pagination'];?>
        </ul>
    </nav>
</div>