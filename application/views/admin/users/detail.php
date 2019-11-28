<?php
//print_r($data);
/**
 */?>
<h2>회원 정보</h2>
<h3>기본 정보</h3>
<table class="table table-bordered table-hover">
    <tr>
        <th>회원 번호</th>
        <td><?=$data['id']?></td>
    </tr>

    <tr>
        <th>레벨</th>
        <td><?=$data['level']?></td>
    </tr>
    <tr>
        <th>로그인 아이디</th>
        <td><?=$data['username']?></td>
    </tr>
    <tr>
        <th>닉네임</th>
        <td><?=$data['nickname']?></td>
    </tr>
    <tr>
        <th>실명</th>
        <td><?=$data['realname']?></td>
    </tr>
    <tr>
        <th>이메일</th>
        <td><?=$data['email']?></td>
    </tr>

    <!----->
    <tr>
        <th>가입 날짜</th>
        <td><?=$data['created']?></td>
    </tr>

    <tr>
        <th>sns</th>
        <td><?=$data['sns_type']?></td>
    </tr>
    <tr>
        <th>sns 가입 날짜</th>
        <td><?=$data['sns_crt_date']?></td>
    </tr>


    <!----->
    <tr>
        <th>최근 ip</th>
        <td><?=$data['last_ip']?></td>
    </tr>
    <tr>
        <th>최근 로그인 </th>
        <td><?=$data['last_login']?></td>
    </tr>
    <!--본인인증-->
    <tr>
        <th>인증 여부</th>
        <td><?php if($data['verify']=='1'){
            echo '인증 됨';
            }else{
                echo '인증 안 됨';
            }?></td>
    </tr>
    <tr>
        <th>성인 </th>
        <td><?php if($data['adult']=='1'){
                echo '성인';
            }else{
                echo '미성년자';
            }?></td>
    </tr>
</table>
<h3>소속 팀</h3>
<a href="/admin/member/lists/1/q?user_id=<?=$data['id']?>" class="btn btn-sm btn-outline-secondary">확인하기</a>
<h3>작성 프로그램</h3>
<a href="/admin/program/lists/1/q?user_id=<?=$data['id']?>" class="btn btn-sm btn-outline-secondary">확인하기</a>
<h3>작성 후기</h3>
<a href="/admin/after/lists/1/q?user_id=<?=$data['id']?>" class="btn btn-sm btn-outline-secondary">확인하기</a>

<hr>

<h3>레벨 변경</h3>

<form action="/admin/users/level/" method="post">
    <select name="level" class="form-control">
        <?php for($i=0; $i<9; $i++){?>
            <option value="<?=$i?>"><?=$i?></option>
        <?php }?>

    </select>
    <input type="hidden" name="user_id" value="<?=$data['id']?>">
    <input type="submit"  class="btn btn-outline-secondary  btn-sm" value="변경">
</form>

<hr>

<h3>탈퇴</h3>

<form action="/admin/users/drop/" method="post">
    <input type="hidden" name="user_id" value="<?=$data['id']?>">
    <input type="submit"  class="btn btn-outline-danger  btn-user-drop" value="탈퇴">
</form>


<a href="/admin/users/" class="btn btn-sm btn-outline-secondary">목록으로</a>