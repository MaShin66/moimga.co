
$('#email').keyup(function () {
    email_regex();
    check_gen_email();

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
function check_gen_email(){
    var email = $('#email').val();
    var moim_id = $('#moim_id').val();
    $.ajax({
        type: "POST",
        url: '/auth/find_gen_user/'+moim_id,
        data: {email: email},
        success: function (data) {
            data = Number(data);
            if(data==-1){
                //이미 지정 되어있음

                document.getElementById('email_dup_error').innerHTML =  email+'은 이미 파트너로 지정되어있습니다. 다른 이메일을 입력해주세요.';
                document.getElementById('user_id').value= null;

            }else if(data==0){ //검색 결과 없음

                document.getElementById('email_dup_error').innerHTML =  email+'로 가입된 사용자를 찾을 수 없습니다.';
                document.getElementById('user_id').value= null;
            }else{ //지정 가능

                console.log(data);
                document.getElementById('email_dup_error').innerHTML = email+'을 파트너로 지정합니다.';
                document.getElementById('user_id').value =data;
            }

        },
        error : function (jqXHR, errorType, error) {
            console.log(errorType + ": " + error);
        }
    });

}