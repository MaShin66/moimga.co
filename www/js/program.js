
$(document).ready(function () {
    var program_id = $('#program_id').val();
    $.ajax({
        type: "POST",
        url: "/program/get_geolocation/",
        data: { program_id: program_id},
        success: function (data) {
            var result = JSON.parse(data);
            console.log(result);
            if(result.latitude==null){
                console.log('불러올 수 없습니다.'); //내 아이디가 아님
                document.getElementsByClassName('map_wrap')[0].style.display = 'none'; //딱 하나있는거 지운다
            }else{

                var container = document.getElementById('map');

                kakao.maps.load(function() {
                    var options = { //지도를 생성할 때 필요한 기본 옵션
                        center: new kakao.maps.LatLng(result.latitude, result.longitude),
                        level: 3
                    };

                    var map = new kakao.maps.Map(container, options);

                    var markerPosition  = new kakao.maps.LatLng(result.latitude, result.longitude);
                    var marker = new kakao.maps.Marker({
                        position: markerPosition
                    });

                    marker.setMap(map);

                });
            }
        },
        error : function (jqXHR, errorType, error) {
            console.log(errorType + ": " + error);
        }
    });
});