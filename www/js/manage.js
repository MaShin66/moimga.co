
$('.btn-delete').on('click',function(e){
    var answer= confirm('정말 삭제하시겠습니까?');
    if(!answer) {
        e.preventDefault();
    }
});

function deposit_status(app_id, form_id,status) {

    $.ajax({
        type: "POST",
        url: '/manage/deposit/status/'+app_id+'?form_id='+form_id,
        data: {status : status},
        success: function (data) {
            data = data.trim();

            console.log('this',data);
            switch (data){
                case 'done':
                    alert('입금확인이 완료되었습니다.');
                    break;
                case 'pending':
                    alert('입금확인 상태가 대기로 변경되었습니다.');
                    break;
                case 'access':
                    alert('권한이 없습니다.');

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