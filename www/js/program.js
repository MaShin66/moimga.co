
function program_heart(program_id) {
    var heart_cnt = Number($('#heart_cnt').text());

    $.ajax({
        type: "post",
        url: '/program/heart/'+program_id,
        success: function (data) {
            var result = data.trim();
            console.log(result);
            switch (result){

                case 'done':
                    $('.btn-heart').addClass('btn-heart-active').removeClass('btn-heart');
                    $('#heart_cnt').text(heart_cnt+1);
                    break;
                case 'cancel':

                    $('.btn-heart-active').addClass('btn-heart').removeClass('btn-heart-active');
                    $('#heart_cnt').text(heart_cnt-1);
                    alert('하트가 취소되었습니다.');
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