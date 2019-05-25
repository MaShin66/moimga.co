
function moim_like(moim_id) {
    var like_cnt = Number($('#like_cnt').text());

    $.ajax({
        type: "post",
        url: '/moim/like/'+moim_id,
        success: function (data) {
            var result = data.trim();
            console.log(result);
            switch (result){

                case 'done':
                    $('.btn-like').addClass('btn-like-active').removeClass('btn-like');
                    $('#like_cnt').text(like_cnt+1);
                    break;
                case 'cancel':

                    $('.btn-like-active').addClass('btn-like').removeClass('btn-like-active');
                    $('#like_cnt').text(like_cnt-1);
                    alert('추천이 취소되었습니다.');
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