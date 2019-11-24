<div class="cont-padding">
    <div class="header_box header_space"></div>
    <h1 class="top_title">팀 목록</h1>
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