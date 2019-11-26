
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
    var team_id = $('#team_id').val();
    $.ajax({
        type: "POST",
        url: '/auth/find_gen_user/'+team_id,
        data: {email: email},
        success: function (data) {
            data = Number(data);
            switch (data){
                case -1:

                    document.getElementById('email_dup_error').innerHTML =  email+'은 이미 팀 멤버로 지정되어있습니다. 다른 이메일을 입력해주세요.';
                    document.getElementById('user_id').value= null;
                    break;
                case -2:

                    document.getElementById('email_dup_error').innerHTML =  email+'은 팀 대표입니다. 팀 대표는 이미 팀 멤버입니다.';
                    document.getElementById('user_id').value= null;
                    break;
                case 0:

                    document.getElementById('email_dup_error').innerHTML =  email+'로 가입된 사용자를 찾을 수 없습니다.';
                    document.getElementById('user_id').value= null;
                    break;
                default:
                    console.log(data);
                    document.getElementById('email_dup_error').innerHTML = email+'을 팀 멤버로 지정합니다.';
                    document.getElementById('user_id').value =data;
                    break;
            }

        },
        error : function (jqXHR, errorType, error) {
            console.log(errorType + ": " + error);
        }
    });

}