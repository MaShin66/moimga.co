//email 형식에 맞는지 ajax

$('#email').keyup(function () {
    email_regex();
    check_dup_email();

});


$('.unregi_ok').on('click',function(e){
    var answer= confirm('정말 탈퇴하시겠습니까? 탈퇴하신 계정은 절대 복구되지 않습니다.');
    if(answer){
        //alert('Devared');
    }
    else{
        e.preventDefault();
    }
});

function email_regex() {

    var email = $('#email').val();
    var regex=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
    var result = '';

    if(regex.test(email) === false) {
        document.getElementById('email_error').innerHTML = '잘못된 이메일 형식입니다.';
        result ='email';
    }else{
        document.getElementById('email_error').innerHTML = '';
    }
    return result;
}
//email 중복인지
function check_dup_email(){
    var email = $('#email').val();
    var result = '';
    $.ajax({
        type: "POST",
        url: '/auth/check_email_dup',
        data: {email: email},
        success: function (data) {
            if(data!=0) { //있는 경우
                console.log(data);
                document.getElementById('email_dup_error').innerHTML = data+'는 이미 가입 된 이메일입니다. 다른 이메일 주소를 입력해주세요.';
                result ='dup';
                console.log(data);
            }else{ //중복 없음. 가입 가능

                document.getElementById('email_dup_error').innerHTML = '';
            }
        },
        error : function (jqXHR, errorType, error) {
            console.log(errorType + ": " + error);
        }
    });
    return result;

}
function pw_confirm(){

    var result = '';
    var origin_password = $('#password').val();
    var confirm_password =$('#confirm_password').val();

    if(origin_password!=confirm_password) {
        document.getElementById('pw_error').innerHTML = '비밀번호가 서로 다릅니다.';
        result ='pw';
    }else{
        document.getElementById('pw_error').innerHTML = '';
    }
    return result;
}


//비밀번호 맞는지
$('#confirm_password').keyup(function () {
    pw_confirm();

});

$('#register_ok').click(function (e) {
    //제대로 다 입력했는지 확인하기
    var email_result = email_regex();
    var dup_result = check_dup_email();
    var pw_result =  pw_confirm();
    var agree_checked = $('input[name=regi_agree_radio]:checked').val();
    if(agree_checked==undefined||agree_checked=='no'){
        alert('개인정보 수집·이용 동의 항목에 동의가 필요합니다.');
        e.preventDefault();
    }
    if(email_result!=''){
        alert('이메일을 정확히 입력해주세요.');
        e.preventDefault();
    }
    if(dup_result!=''){
        alert('이미 가입 된 이메일입니다. 다른 이메일 주소를 입력해주세요.');
        e.preventDefault();
    }
    if(pw_result!=''){
        alert('비밀번호가 서로 다릅니다.');
        e.preventDefault();
    }
});