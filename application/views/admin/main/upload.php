<h2>메인 등록 <?=$data['submit_txt']?></h2>

<form action="/admin/main/upload/" method="post" enctype="multipart/form-data">
    <h3>콘텐츠</h3>
    <div class="form-group row">
        <label for="contents_cate_1" class="col-sm-2 col-form-label">콘텐츠 카테고리 1</label>
        <div class="col-sm-10">
            <select name="contents_cate_1" class="form-control">
                <?php foreach ($contents_cate_list as $key=>$item){?>
                    <option value="<?=$item['contents_category_id']?>"
                    <?php if($data['contents_cate_1']==$item['contents_category_id']){ echo 'selected';}?>>
                        <?=$item['title']?>
                    </option>
                <?php }?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="contents_thumb_1" class="col-sm-2 col-form-label">콘텐츠 카테고리 1 섬네일</label>
        <div class="col-sm-10">
            <input type="file" name="contents_thumb_1" class="form-control"/>
            <?php if($data['write_type']=='modify'){
                if($data['contents_thumb_1']!=null){?>

                    <img  class="upload_thumbs_sm" src="../../<?=$data['contents_thumb_1']?>" alt="팀 섬네일">
                    <div class="prod_upload_guide"><i class="fas fa-info-circle"></i> 지정된 섬네일이 있습니다. 다시 올리고 싶으시면 파일을 다시 선택해주세요.</div>
                <?php }
            }?>
        </div>
    </div>
    <div class="form-group row">
        <label for="contents_cate_1" class="col-sm-2 col-form-label">콘텐츠 카테고리 2</label>
        <div class="col-sm-10">
            <select name="contents_cate_2" class="form-control">
                <?php foreach ($contents_cate_list as $key2=>$item2){?>
                    <option value="<?=$item2['contents_category_id']?>"
                        <?php if($data['contents_cate_2']==$item2['contents_category_id']){ echo 'selected';}?>>
                        <?=$item2['title']?>
                    </option>
                <?php }?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="contents_thumb_2" class="col-sm-2 col-form-label">콘텐츠 카테고리 2 섬네일</label>
        <div class="col-sm-10">
            <input type="file" name="contents_thumb_2" class="form-control"/>
            <?php if($data['write_type']=='modify'){
                if($data['contents_thumb_2']!=null){?>

                    <img  class="upload_thumbs_sm" src="../../<?=$data['contents_thumb_2']?>" alt="팀 섬네일">
                    <div class="prod_upload_guide"><i class="fas fa-info-circle"></i> 지정된 섬네일이 있습니다. 다시 올리고 싶으시면 파일을 다시 선택해주세요.</div>
                <?php }
            }?>
        </div>
    </div>

    <h3>스토어</h3>


    <div class="form-group row">
        <label for="store_cate_1" class="col-sm-2 col-form-label">스토어 카테고리 1</label>
        <div class="col-sm-10">
            <select name="store_cate_1" class="form-control">
                <?php foreach ($store_cate_list as $key=>$item){?>
                    <option value="<?=$item['store_category_id']?>"
                        <?php if($data['store_cate_1']==$item['store_category_id']){ echo 'selected';}?>>
                        <?=$item['title']?>
                    </option>
                <?php }?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="store_thumb_1" class="col-sm-2 col-form-label">스토어 카테고리 1 섬네일</label>
        <div class="col-sm-10">
            <input type="file" name="store_thumb_1" class="form-control"/>
            <?php if($data['write_type']=='modify'){
                if($data['store_thumb_1']!=null){?>

                    <img  class="upload_thumbs_sm" src="../../<?=$data['store_thumb_1']?>" alt="팀 섬네일">
                    <div class="prod_upload_guide"><i class="fas fa-info-circle"></i> 지정된 섬네일이 있습니다. 다시 올리고 싶으시면 파일을 다시 선택해주세요.</div>
                <?php }
            }?>
        </div>
    </div>
    <div class="form-group row">
        <label for="store_cate_1" class="col-sm-2 col-form-label">스토어 카테고리 2</label>
        <div class="col-sm-10">
            <select name="store_cate_2" class="form-control">
                <?php foreach ($store_cate_list as $key2=>$item2){?>
                    <option value="<?=$item2['store_category_id']?>"
                        <?php if($data['store_cate_2']==$item2['store_category_id']){ echo 'selected';}?>>
                        <?=$item2['title']?>
                    </option>
                <?php }?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="store_thumb_2" class="col-sm-2 col-form-label">스토어 카테고리 2 섬네일</label>
        <div class="col-sm-10">
            <input type="file" name="store_thumb_2" class="form-control"/>
            <?php if($data['write_type']=='modify'){
                if($data['store_thumb_2']!=null){?>

                    <img  class="upload_thumbs_sm" src="../../<?=$data['store_thumb_2']?>" alt="팀 섬네일">
                    <div class="prod_upload_guide"><i class="fas fa-info-circle"></i> 지정된 섬네일이 있습니다. 다시 올리고 싶으시면 파일을 다시 선택해주세요.</div>
                <?php }
            }?>
        </div>
    </div>

    <?php if(!is_null($data['main_id'])){?>
        <input type="hidden" name="main_id" value="<?=$data['main_id']?>">

    <?php }?>
    <input type="hidden" name="write_type" value="<?=$data['write_type']?>">
    <input type="submit"  class="btn btn-action" value="<?=$data['submit_txt']?>">
</form>