
var url = window.location.href;

if(url.indexOf('auth/login') > 0) {

    var referrer =  document.referrer;
    $.cookie("visits", referrer);
    var new_ref = $.cookie("visits");

    Kakao.init('471ff0833124f6d71e84242d4144e67a');

    function loginWithKakao() {
        $('.fade_form').show();
        Kakao.Auth.login({
            success: function(authObj) {
                Kakao.API.request({
                    url: '/v2/user/me',
                    success: function(res) {
                        var unique_id = res.id;
                        var email = res.properties.email;
                        var name = res.properties.nickname;
                        var sns_type = 'kakao';
                        console.log(res);
                        sns_login_ajax(name, email,sns_type,new_ref,unique_id);
                    },
                    fail: function(err) {
                        alert('오류가 발생했습니다. 관리자에게 문의하세요.');
                        //alert(JSON.stringify(err));
                    }
                });
            },
            fail: function(err) {
                alert(JSON.stringify(err));
            }
        });

    };

    console.log('로그인 대기');
    function onSignIn(googleUser) {

        $('.fade_form').show();
        var profile = googleUser.getBasicProfile();
        var unique_id = profile.getId();
        var email = profile.getEmail();
        var name = profile.getName();
        var sns_type = 'google';

        sns_login_ajax(name, email, sns_type,new_ref,unique_id);

    }

    function signOut() {
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
            console.log('User signed out.');
            alert('로그아웃 되었습니다.');
            location.href="/auth/logout";

        });
    }
    var googleUser = {};
    var startApp = function() {

        gapi.load('auth2', function(){
            // Retrieve the singleton for the GoogleAuth library and set up the client.
            auth2 = gapi.auth2.init({
                client_id: '71893340918-0eigb5rkfq8spmeh8vbapc5qqpu5fbel.apps.googleusercontent.com',
                cookiepolicy: 'single_host_origin',
                // Request scopes in addition to 'profile' and 'email'
                //scope: 'additional_scope'
            });
            attachSignin(document.getElementById('customGoogleBtn'));
        });
    };

    function attachSignin(element) {
        //console.log(element.id);
        auth2.attachClickHandler(element, {},
            function(googleUser) {
                onSignIn(googleUser);
            }, function(error) {
            alert('로그인 창을 닫았습니다. 다시 로그인을 시도해주세요.');
                console.log(JSON.stringify(error, undefined, 2));
            });
    }

    startApp();

}

function sns_login_ajax(name, email, sns_type,referrer,unique_id){
    var remember =$('.input_check:checked').val();
    if(remember!=1){
        remember = 0;
    }

    //이거 ajax로 보내서 user_id, email에 있는지 확인하는것만 보내면 됨..
    $.ajax({
        type: "POST",
        url: '/auth/sns_login_check',
        data: {sns_id : name, sns_email: email, type: 'unknown',remember:remember, sns_type: sns_type,unique_id:unique_id},
        success: function (data) {
            data = data.trim();

            console.log('this',data);
            switch (data){
                case 'google':
                    alert('회원가입이 완료되었습니다. 마이페이지에서 필수 정보를 모두 채워주셔야 경험 해부가 가능합니다. 마이페이지로 이동합니다.');
                    window.location.href='http://jobslearnplace.com/mypage';
                    break;
                case 'kakao':
                    alert('회원가입이 완료되었습니다. 마이페이지에서 이메일 등록을 해주세요.');
                    window.location.href='http://jobslearnplace.com/mypage';
                    break;
                case 'login':
                    if(referrer==undefined||referrer==''){
                        referrer = 'http://jobslearnplace.com/';
                    }
                   window.location.href=referrer;
                    break;
                default: //가입했는데 카카오인 경우
                    console.log(data);
                    alert('에러가 발생했습니다. 메인 페이지로 이동합니다.');
                    window.location.href='http://jobslearnplace.com/';
                    break;

            }
        },
        error : function (jqXHR, errorType, error) {
            console.log(errorType + ": " + error);
        }
    });

}
