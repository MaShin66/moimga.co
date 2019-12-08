
var url = window.location.href;

//fb login

window.fbAsyncInit = function() {
    console.log('FB 로그인 대기');

    FB.init({
        appId      : '895120010883967',
        cookie     : true,                     // Enable cookies to allow the server to access the session.
        xfbml      : true,                     // Parse social plugins on this webpage.
        version    : 'v5.0'           // Use this Graph API version for this call.
    });

};


(function(d, s, id) {                      // Load the SDK asynchronously
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/es_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//네이버 로그인
if(url.indexOf('auth/login') > 0) {

    var referrer =  document.referrer;
    $.cookie("visits", referrer);
    var new_ref = $.cookie("visits");

    var naverLogin = new naver.LoginWithNaverId(
        {
            clientId: "J0HOemTO7_tr6uUqVzGO",
            callbackUrl: "https://moimga.co/auth/naver_login",
            isPopup: false, /* 팝업을 통한 연동처리 여부 */
        }
    );

    /* 설정정보를 초기화하고 연동을 준비 */
    naverLogin.init();

    Kakao.init('b96a3667e5757f2d026734edddb51d5e');

    function loginWithKakao() {
        $('.fade_form').show();
        Kakao.Auth.login({
            success: function (authObj) {
                Kakao.API.request({
                    url: '/v2/user/me',
                    success: function (res) {
                        var unique_id = res.id;
                        var email = res.properties.email;
                        var name = res.properties.nickname;
                        var sns_type = 'kakao';
                        console.log(name);
                        console.log(res);
                        sns_login_ajax(name, email, sns_type, new_ref, unique_id);
                    },
                    fail: function (err) {
                        alert('오류가 발생했습니다. 관리자에게 문의하세요.');
                        //alert(JSON.stringify(err));
                    }
                });
            },
            fail: function (err) {
                alert(JSON.stringify(err));
            }
        });
    }
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
                client_id: '918014591656-i1jnoekh0h75dd8ia3hlkv60lejha5mq.apps.googleusercontent.com',
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
                alert(JSON.stringify(error, undefined, 2));
            });
    }

    startApp();

}else if(url.indexOf('naver_login')>0){
    var new_ref = $.cookie("visits");
    if(error_param!='' || error_param=='access_denied'){
        console.log('you...');
        $('.naver_denied').show();
        $('.naver_logging_in').hide();
    }
    var naverLogin = new naver.LoginWithNaverId(
        {
            clientId: "J0HOemTO7_tr6uUqVzGO",
            callbackUrl: "https://moimga.co/auth/naver_login",
            isPopup: false,
            callbackHandle: true
        }
    );

    /* (3) 네아로 로그인 정보를 초기화하기 위하여 init을 호출 */
    naverLogin.init();

    /* (4) Callback의 처리. 정상적으로 Callback 처리가 완료될 경우 main page로 redirect(또는 Popup close) */
    naverLogin.getLoginStatus(function (status) {
        if (status) {
            var email = naverLogin.user.getEmail();
            var name = naverLogin.user.getName();
            var unique_id = naverLogin.user.getId();
            var sns_type = 'naver';
            sns_login_ajax(name, email,sns_type,new_ref,unique_id);
            document.getElementById('console_html').innerText=email.name.unique_id;
        } else {
            document.getElementById('console_html').innerText='AccessToken이 올바르지 않습니다.'+status;
            console.log("AccessToken이 올바르지 않습니다. 다른 브라우저에서 개인정보 보호탭으로 로그인을 시도해보세요.");
        }
    });

}else{
    console.log(new_ref);

}

function facebook_login() {
    FB.login(function (res) {
            if (res.authResponse) {
                FB.api('/me', function(res) {
                    console.log(res);
                    var email = res.email;
                    var name = res.name;
                    var unique_id = res.id;
                    var sns_type = 'facebook';
                    sns_login_ajax(name, email,sns_type,new_ref,unique_id);

                });
            } else {
                console.log('User cancelled login or did not fully authorize.');
            }
        },
        {scope: 'email',
            return_scopes: true}
    );
}
$('#naverIdLogin_loginButton').click(function () {

    $('.fade_form').show();
});

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
            // console.log(JSON.parse(data));
            data = data.trim();
            console.log(data);
            switch (data){
                case 'regi':
                    alert('회원가입이 완료되었습니다.');
                    break;
                case 'login':

                    break;
                default: //가입했는데 카카오인 경우
                    console.log(data);
                    alert('에러가 발생했습니다. 메인 페이지로 이동합니다.');
                    break;

            }
            if(referrer==undefined||referrer==''){
                referrer = 'https://moimga.co';
            }
            window.location.href=referrer;

        },
        error : function (jqXHR, errorType, error) {
            console.log(errorType + ": " + error);
        }
    });

}
