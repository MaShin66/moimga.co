<?php
/**
 */?>
<h2>팀 정보</h2>
여기에 팀 정보가..

<h3>기본 정보</h3>
<div class="">
    <form action="/admin/team/delete/" method="post">
        <input type="hidden" name="team_id" value="<?=$team_info['team_id']?>">
        <input type="submit"  class="btn btn-outline-danger btn-delete" value="팀 삭제">
    </form>
</div>

<div class="">
    팀을 삭제하시면 .. 신중하게 사용해라 어ㅉㅓ구 저쩌구..
</div>

<div class="">
    상태: <?=$this->lang->line($team_info['status'])?>
</div>
<div class="">
    <form action="/admin/set_status/" method="post">
        <input type="hidden" name="unique_id" value="<?=$team_info['team_id']?>">
        <input type="hidden" name="type" value="team">

        <?php if($team_info['status']=='on'){?>

            <input type="hidden" name="status" value="off">
            <input type="submit"  class="btn btn-outline-secondary btn-off" value="비공개로 변경">

        <?php }else if($team_info['status']=='off'){?>

            <input type="hidden" name="status" value="on">
            <input type="submit"  class="btn btn-outline-secondary btn-on" value="공개로 변경">

        <?php }?>
    </form>
</div>

<h3>프로그램</h3>
<a href="/admin/program/lists/1/q?team_id=<?=$team_info['team_id']?>">프로그램 목록</a>

<ol>
    <?php foreach ($program_list as $key=>$item){?>
        <li>  <a href="/manage/program/detail/<?=$item['program_id']?>"><?=$item['title']?></a></li>
    <?php }?>
</ol>

<h3>팀 멤버</h3>
<a href="/admin/member/lists/1/q?team_id=<?=$team_info['team_id']?>">팀 멤버 목록</a>

<table class="table table-bordered table-hover">
    <tr>
        <th>이름</th>
        <th>권한</th>
        <th>지정날짜</th>
        <th>보기</th>
    </tr>
    <?php if(count($member_list)==0){?>
        <tr>
            <td colspan="5">팀 멤버가 없습니다.</td>
        </tr>
    <?php }else{
        foreach ($member_list as $key=>$item){?>

            <tr>
                <td><a href="/admin/member/detail/<?=$item['team_member_id']?>"><?=$item['realname']?></a></td>
                <td>
                    <?php if($item['type']==2){
                        echo '멤버';
                    }else{
                        echo '대표';
                    }?>

                </td>
                <td><?=$item['set_date']?></td>
                <td><a href="/admin/member/detail/<?=$item['team_member_id']?>" class="btn btn-sm btn-outline-secondary">보기</a></td>
            </tr>

        <?php }
    }?>

</table>
<h3>팀 블로그</h3>

    <a href="/admin/team_blog/lists/1/q?team_id=<?=$team_info['team_id']?>">포스트 목록</a>

<table class="table table-bordered table-hover">
    <tr>
        <th>번호</th>
        <th>제목</th>
        <th>작성날짜</th>
        <th>조횟수</th>
        <th>보기</th>
    </tr>
    <?php if(count($blog_list)==0){?>
        <tr>
            <td colspan="5">포스트가 없습니다.</td>
        </tr>
    <?php }else{
        foreach ($blog_list as $b_key=>$b_item){?>

            <tr>
                <td><?=$b_item['team_blog_id']?></td>
                <td><a href="/admin/team_blog/detail/<?=$b_item['team_blog_id']?>"><?=$b_item['title']?></a></td>
                <td><?=$b_item['crt_date']?></td>
                <td><?=$b_item['hit']?></td>
                <td><a href="/admin/team_blog/detail/<?=$b_item['team_blog_id']?>" class="btn btn-sm btn-outline-secondary">보기</a></td>
            </tr>

        <?php }
    }?>

</table>

<h3>사용자 후기</h3>

<ul>
    <li>이 팀에 작성된 공개된 후기만 출력됩니다.</li>
    <li>후기는 관리할 수 없습니다.</li>
</ul>

<a href="/admin/after/lists/1/q?team_id=<?=$team_info['team_id']?>">후기 목록</a>
<table class="table table-bordered table-hover">

    <tr>
        <th>번호</th>
        <th>제목</th>
        <th>작성날짜</th>
        <th>조횟수</th>
        <th>보기</th>
    </tr>
    <?php if(count($after_list)==0){?>
        <tr>
            <td colspan="5">후기가 없습니다.</td>
        </tr>
    <?php }else{
        foreach ($after_list as $a_key=>$a_item){?>

            <tr>
                <td><?=$a_item['after_id']?></td>
                <td><a href="/admin/after/detail/<?=$a_item['after_id']?>"><?=$a_item['title']?></a></td>
                <td><?=$a_item['crt_date']?></td>
                <td><?=$a_item['hit']?></td>
                <td><a href="/admin/after/detail/<?=$a_item['after_id']?>" class="btn btn-sm btn-outline-secondary">보기</a></td>
            </tr>

        <?php }
    }?>

</table>
<hr>
<a href="/admin/team/" class="btn btn-sm btn-outline-secondary">목록으로</a>