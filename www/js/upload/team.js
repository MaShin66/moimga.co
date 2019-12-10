

function submit_team() {
    //url에 숫자, 영문만 가능하게.
    var team_url = $('#team_url').val();
    var regex_url = /^[A-Za-z0-9+]*$/;
    if (!regex_url.test(team_url)) {
        alert('URL에는 숫자, 영문만 들어갈 수 있습니다.');
        event.preventDefault();
        return false;
    }
    //글자 수 세기

    if (team_url.length<2) {
        alert('URL을 두글자 이상으로 지정해주세요.');
        event.preventDefault();
        return false;
    }
    dup_url_check(); // url check

    //제목에 <>, 불가능
    var team_title = $('#team_title').val();

    if(team_title.match(/,/)||team_title.match(/"/)||team_title.match(/'/)||team_title.match(/</)||team_title.match(/>/)){
        alert('팀 제목에는 콤마(,), 큰따옴표, 작은따옴표, 부등호(<,>)가 들어갈 수 없습니다.');
        event.preventDefault();
        return false;
    }
    $('#input_mirror').val($('.ql-editor').html());

    $('#team_form').submit();
}


$("#team_title").keyup(function(e) {

    var input_text = $(this).val();
    if(input_text.match(/,/)||input_text.match(/"/)||input_text.match(/'/)||input_text.match(/</)||input_text.match(/>/)){
        alert('팀 제목에는 콤마(,), 큰따옴표, 작은따옴표, 부등호(<,>)가 들어갈 수 없습니다.');
        return false;
        e.preventDefault();
    }
});

$("#team_url").keyup(function(e) {

    var team_url = $(this).val();
    var regex_url = /^[A-Za-z0-9+]*$/;
    if (!regex_url.test(team_url)) {
        document.getElementById('url_dup_error').innerHTML =  'URL에는 숫자, 영문만 들어갈 수 있습니다';

        e.preventDefault();
        return false;
    }
    dup_url_check();
});


/*url dup check*/

function dup_url_check() {

    var team_url = $('#team_url').val();
    if(team_url!='' && team_url.length>1){
        $.ajax({
            type: "POST",
            url: '/search/team_url/',
            data: {team_url: team_url},
            success: function (data) {
                data = JSON.parse(data);
                console.log(data);
                if(!data){ //데이터가 있으면

                    document.getElementById('url_dup_error').innerHTML = team_url+'을 URL로 사용할 수 있습니다.';
                }else{
                    document.getElementById('url_dup_error').innerHTML = '중복된 URL이 있습니다. 다른 URL을 입력해주세요.';
                }

            },
            error : function (jqXHR, errorType, error) {
                console.log(errorType + ": " + error);
            }
        });
    }else{

        document.getElementById('url_dup_error').innerHTML = '팀 URL을 두글자 이상으로 지정해주세요.';
    }

}