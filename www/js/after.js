
function search_program() {

    var list_div = $('#search_list');
    list_div.empty(); //초기화
    var search = $('#program_title').val();
    if(search==''){
        alert('검색어를 입력해주세요');
    }else{
        $.ajax({
            type: "post",
            url: '/search/title/program',
            data: {search: search},
            success: function (data) {
                var result = JSON.parse(data);
                console.log(result);
                if(!result){
                    //검색 결과 없음
                    //alert('검색어를 입력해주세요');
                }else{
                    var append_array = null;
                    result.forEach(function (element) {
                        list_div.append(
                            '<div class=""  onclick="set_program_id('+element.program_id+')">'+element.title+'</div>'
                        );
                    });

                }
            },
            error : function (jqXHR, errorType, error) {
                console.log(errorType + ": " + error);
            }
        });
    }

}

function set_program_id(program_id) {
    $('#program_title').val(program_id); //title 도 쓰기
    $('#program_id').val(program_id);
    alert('프로그램이 설정되었습니다.');
}

function submit_after() {
    $('#after_form').submit();
}