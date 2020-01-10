$(document).ready(function() {
    var event_date_val='';
    var open_date_val='';
    var close_date_val='';

    $(".event_date").asDatepicker({
        namespace: 'calendar',
        lang: 'ko',
        position: 'bottom',
        onceClick: true,
        date: event_date_val
    });

    $(".open_date").asDatepicker({
        namespace: 'calendar',
        lang: 'ko',
        position: 'bottom',
        onceClick: true,
        date: open_date_val
    });

    $(".close_date").asDatepicker({
        namespace: 'calendar',
        lang: 'ko',
        position: 'bottom',
        onceClick: true,
        date: close_date_val
    });
    $('.calendar').click(function(){
        $(this).asDatepicker('show');
    });

});


function open_postcode() {
    new daum.Postcode({
        oncomplete: function(data) {
            var fullAddr = ''; // 최종 주소 변수
            var extraAddr = ''; // 조합형 주소 변수

            if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                fullAddr = data.roadAddress;

            } else { // 사용자가 지번 주소를 선택했을 경우(J)
                fullAddr = data.jibunAddress;
            }

            // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
            if(data.userSelectedType === 'R'){//법정동명이 있을 경우 추가한다.
                if(data.bname !== ''){
                    extraAddr += data.bname;
                }
                // 건물명이 있을 경우 추가한다.
                if(data.buildingName !== ''){
                    extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                }
                // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
            }

            // 우편번호와 주소 정보를 해당 필드에 넣는다.
            document.getElementById('postcode').value = data.zonecode; //5자리 새우편번호 사용
            document.getElementById('address').value = fullAddr;

            // 커서를 상세주소 필드로 이동한다.
            document.getElementById('address2').focus();
        }
    }).open();
}