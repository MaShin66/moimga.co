
function set_subscribe(team_id) {

    $.ajax({
        type: "post",
        url: '/subscribe/register/',
        data: {team_id : team_id},
        success: function (data) {
            var result = data.trim();
            console.log(result);
            switch (result){

                case 'done':
                    $('.subscribe_btn').html('<i class="fas fa-bookmark subscribe_active"></i>');
                    //
                    // gtag('event', 'subscribe', {
                    //     'event_category': 'product',
                    //     'event_label': team_id,
                    //     'value': null
                    // });
                    alert('구독을 시작했습니다.');
                    break;
                case 'cancel':
                    $('.subscribe_btn').html('<i class="far fa-bookmark"></i>');
                    alert('구독이 취소되었습니다.');
                    break;
                case 'login':
                    alert('로그인이 필요합니다.');
                    break;
                default:
                    alert('에러가 발생했습니다.');
                    break;
            }

        },
        error : function (jqXHR, errorType, error) {
            console.log(errorType + ": " + error);
        }
    });
}
