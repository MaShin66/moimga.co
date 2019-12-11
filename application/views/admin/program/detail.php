<?php
//print_r($data);
/**
 */?>
<h2>팀 포스트 정보</h2>
<h3>기본 정보</h3>
<table class="table table-bordered table-hover">
    <tr>
        <th>프로그램 번호</th>
        <td><?=$data['program_id']?></td>
    </tr>
    <tr>
        <th>글쓴 사람</th>
        <td><a href="/admin/users/detail/<?=$data['user_id']?>"><?=$data['realname']?> (<?=$data['nickname']?>)</a> <br>
        권한: <?=$data['member_type']?></td>
    </tr>

    <tr>
        <th>팀</th>
        <td><?=$team_info['name']?><br>

            <a href="/admin/team/detail/<?=$data['team_id']?>" target="_blank">(관리자페이지)</a> | <a href="/@<?=$team_info['url']?>" target="_blank">(팀 페이지)</a>
        </td>
    </tr>
    <tr>
        <th>하트</th>
        <td><?=$data['heart_count']?></td>
    </tr>

    <tr>
        <th>참가 인원</th>
        <td>총 <?=$data['participant']?>명</td>
    </tr>

    <tr>
        <th>지역 / 장소</th>
        <td><?=$data['district']?> / <?=$data['venue']?><br>
            <?=$data['address']?></td>
    </tr>
    <tr>
        <th>가격</th>
        <td><?=number_format($data['price'])?>원</td>
    </tr>


    <tr>
        <th>제목</th>
        <td><a href="/@<?=$team_info['url']?>/program/<?=$data['program_id']?>" target="_blank"><?=$data['title']?></a></td>
    </tr>

    <tr>
        <th>조회수</th>
        <td><?=$data['hit']?></td>
    </tr>
    <tr>
        <th>상태</th>
        <td><?=$this->lang->line($data['status'])?>
            <div class="">
                <form action="/admin/set_status/" method="post">
                    <input type="hidden" name="unique_id" value="<?=$data['program_id']?>">
                    <input type="hidden" name="type" value="program">

                    <?php if($data['status']=='on'){?>

                        <input type="hidden" name="status" value="off">
                        <input type="submit"  class="btn btn-outline-secondary btn-off" value="비공개로 변경">

                    <?php }else if($data['status']=='off'){?>

                        <input type="hidden" name="status" value="on">
                        <input type="submit"  class="btn btn-outline-secondary btn-on" value="공개로 변경">

                    <?php }?>
                </form>
            </div>
        </td>
    </tr>
    <tr>
        <th>글 쓴 날짜</th>
        <td><?=$data['crt_date']?></td>
    </tr>

</table>
<h3>날짜</h3>

<table class="table table-bordered table-hover">
    <tr>
        <th>날짜</th>
        <th>시간 (0~24시)</th>
    </tr>

    <?php foreach ($date_info as $ed_key=>$ed_item){?>

        <td><?=$ed_item['date']?></td>
        <td><?=$ed_item['time']?></td>
    <?php }?>

</table>


<h3>QNA</h3>

<table class="table table-bordered table-hover">

    <?php foreach ($qna_info as $qt_key=>$qt_item){?>
        <tr>

            <td>질문: <?=$qt_item['question']?></td>

        </tr>
        <tr>

            <td>답변: <?=$qt_item['answer']?></td>

        </tr>
    <?php }?>
</table>

<h3>이런분들이 오셨으면 좋겠어요</h3>

<ol>
    <?php foreach ($qualify_info as $q_key=>$q_item){?>

        <li><?=$q_item['contents']?></li>
<?php }?>

</ol>

<h3>내용</h3>
<?=$data['contents']?>
<hr>

<div class="">
    <form action="/admin/program/delete/" method="post">
        <input type="hidden" name="program_id" value="<?=$data['program_id']?>">
        <input type="submit"  class="btn btn-outline-danger btn-delete btn-sm" value="삭제">
    </form>
</div>

<h2>삭제</h2>
<ol>
    <li>DB에서 완전히 삭제됩니다. (관리자페이지에서도 확인할 수 없음)</li>
    <li>앞으로 복구가 불가능합니다.</li>

</ol>

<hr>
<a href="/admin/team_blog/" class="btn btn-sm btn-outline-secondary">목록으로</a>