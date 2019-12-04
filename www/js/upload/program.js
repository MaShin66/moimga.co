$(document).ready(function() {

    var today = new Date();
    today.setDate(today.getDate() + 7); //15일 더하여 setting
    var year = today.getFullYear();
    var month = today.getMonth() + 1;
    var day = today.getDate();

    var default_date = year+'-'+ month+'-'+ day;

    //modify, copy, new
    if(window.location.href.indexOf("modify") > -1){

        var program_id = $('#program_id').val();
        //
        // open_date_val = open_date;
        // close_date_val = close_date;

        // setInterval(function() {
        //     autosave('program'); //interval 두고 autosave
        // }, 20000);
        $.ajax({
            type: "POST",
            url: '/program/load_event_date/',
            data: { program_id:program_id},
            success: function (data) {
                var event_list = JSON.parse(data);
                for (key in event_list){

                    var this_key = Number(key)+1;
                    $("#event_date_"+this_key).find('.event_date').asDatepicker({ //initiate 안되는 느낌이네요..
                        namespace: 'calendar',
                        lang: 'ko',
                        position: 'bottom',
                        onceClick: true,
                        date: event_list[key].date
                    }); //팔린게 없으면 이래도 되늗네..
                }
                //
            },
            error : function (jqXHR, errorType, error) {
                console.log(errorType + ": " + error);
            }
        });

    }else{

        $(".event_date ").asDatepicker({
            namespace: 'calendar',
            lang: 'ko',
            position: 'bottom',
            onceClick: true,
            date: default_date //오늘+7
        });

    }
    $('.calendar').click(function(){
        $(this).asDatepicker('show');
    });

});

//비밀 티켓 토글
$('#secretCheckbox').change(function(){
    $('#secret_input_wrap').toggle();
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

$('#template_common').click(function () {
    console.log('sdfs');
    if(!$(this).hasClass('active')){

        var common_text =
            '<p>꼭 입금 후, 폼을 작성해주세요.</p>' +
            '<p>은행 입금 시간으로 인한 폼 연장 문의는 받지 않습니다. 꼭 미리 입금 부탁드려요!</p>' +
            '<p>회차별로 남은 매수가 다릅니다. 티켓 구매 페이지에서 매수를 확인해주세요.</p>'+
            '<br>'+
            '<h3 class="temp_item_wrap"><span style="color:#27c781; font-weight:bold;">□</span>  행사 날짜 </h3>' +
            '<p class="temp_item_cont">2019년 OO월 OO일 (회차가 여러번있는 경우 모두 적어주세요)</p>' +
            '<br>'+
            '<h3 class="temp_item_wrap"><span style="color:#27c781; font-weight:bold;">□</span>  문의</h3>' +
            '<p class="temp_item_cont">0O (트위터 아이디나, 이메일 주소를 적어주세요.)</p>' +
            '<br>'+
            '<h3 class="temp_item_wrap"><span style="color:#27c781; font-weight:bold;">□</span>  입금확인</h3>' +
            '<ul class="temp_item_cont">' +
            '<li>TMM의 <a href="/mypage/ticket" target="_blank" rel="noopener">Mypage(새 창) - 내 티켓</a>에서 확인하실 수 있습니다.</li>' +
            '<li>별도의 입금 확인 메일은 전송되지 않습니다.</li>' +
            '</ul>' +
            '<br>'+
            '<h3 class="temp_item_wrap"><span style="color:#27c781; font-weight:bold;">□</span>  모바일 입장권</h3>' +
            '<ul class="temp_item_cont">' +
            '<li>TMM의 <a href="/mypage/ticket" target="_blank" rel="noopener">Mypage(새 창) - 내 티켓</a>에서 확인하실 수 있습니다.</li>' +
            '</ul>' +
            '<br>'+
            '<h3 class="temp_item_wrap"><span style="color:#27c781; font-weight:bold;">□</span>  본인 확인 번호</h3>' +
            '<ul class="temp_item_cont">' +
            '<li>입장 시 본인확인 번호를 확인합니다. 꼭 적어주세요. (양식은 주최자님이 정해주세요. 예) 숫자 네자리 등)</li>' +
            '<li>(아래는 상황에 맞게 편집하세요.)</li>' +
            '<li>모바일 티켓은 입장 시 확인하지 않습니다. (이 경우에만 남겨주세요.)</li>' +
            '<li>본인 확인 번호와 모바일 티켓을 모두 확인합니다.(이 경우에만 남겨주세요.)</li>' +
            '</ul>' +
            '<br>'+
            '<h3 class="temp_item_wrap"><span style="color:#27c781; font-weight:bold;">□</span>  환불</h3>' +
            '<ul class="temp_item_cont">' +
            '<li>단순 변심으로 인한 환불은 불가능합니다. 신중하게 선택해주세요.</li>' +
            '<li>최소 입장객 미달 시 전체 환불 됩니다.</li>' +
            '</ul>';
        $('.ql-editor').append(common_text);
        $(this).addClass('active');
    }
});

function add_event_date() { //copy에도 동일하게 적용해야함

    var write_type = $('#write_type').val(); //write_type이 new가 아닌경우에 바로 db에
    var now_num = $('.event_date_wrap').children().length; // 현재 갯수 -- 이거는 처음일 때..
    var next_num = Number(now_num+1);
    var common_buttons =  '<div class="col-md-1 col-sm-6 col-6">' +
' <div class="btn btn-outline-action " onclick="copy_event_date('+next_num+');">+ 복사</div>' +
'  </div>'+
'   <div class="col-md-1 col-sm-6 col-6">' +
' <div class="btn btn-outline-red " onclick="delete_event_date('+next_num+');">- 삭제</div>' +
'  </div>';
    var time_option  = null;
    for(var i=0; i<24; i++){
        time_option = time_option + '<option value="'+i+'">'+i+'</option>';
    }

    var today = new Date();
    today.setDate(today.getDate() + 7); //15일 더하여 setting
    var year = today.getFullYear();
    var month = today.getMonth() + 1;
    var day = today.getDate();

    var default_date = year+'-'+ month+'-'+ day;

    //input 구분하기
    if(write_type=='modify'){
        var program_id = $('#program_id').val();

        $.ajax({
            type: "POST",
            url: '/program/add_event_date/',
            data: { program_id:program_id},
            success: function (data) {
                var res = JSON.parse(data);
                switch (res){
                    case 'auth':
                        alert('본인 프로그램에만 추가할 수 있습니다.');
                        break;
                    default:
                        console.log(res);
                        var pdate_id = res;

                        var time_select ='<div class="">' +
                            '<select name="event_time['+pdate_id+']" class="custom-select form-control" id="input_event_time'+next_num+'"  data-event-date-id="'+pdate_id+'">';

                        time_select = time_select + time_option + '</select>시 </div></div>';

                        $('.event_date_wrap').append(
                            '<div class="form-row event_date_add" id="event_date_'+next_num+'">' +
                            '   <div class="form-group col-md-6">' +
                            '       <input type="text" class="form-control input_basic event_date" name="event_date['+pdate_id+']" id="input_event_date'+next_num+'" placeholder="행사 날짜를 선택해주세요.">' +
                            '    </div>' +
                            '    <div class="form-group col-md-4">' +time_select + common_buttons+
                            '</div>'

                        );

                        $("#input_event_date"+next_num).asDatepicker({
                            namespace: 'calendar',
                            lang: 'ko',
                            position: 'bottom',
                            onceClick: true,
                            date:  default_date
                        });

                        break;
                }
                //
            },
            error : function (jqXHR, errorType, error) {
                console.log(errorType + ": " + error);
            }
        });

    }else{
        var time_select ='<div class="">' +
            '<select name="input_event_time[]" class="custom-select form-control" id="input_event_time'+next_num+'">';

        time_select = time_select + time_option + '</select>시 </div></div>';

        $('.event_date_wrap').append(
            '<div class="form-row event_date_add" id="event_date_'+next_num+'">' +
            '   <div class="form-group col-md-6">' +
            '       <input type="text" class="form-control input_basic event_date" name="input_event_date[]" id="input_event_date'+next_num+'" placeholder="행사 날짜를 선택해주세요.">' +
            '    </div>' +
            '    <div class="form-group col-md-4">' +time_select + common_buttons+
            '</div>'

        );

        $("#input_event_date"+next_num).asDatepicker({
            namespace: 'calendar',
            lang: 'ko',
            position: 'bottom',
            onceClick: true,
            date:  default_date
        });
    }


}

function copy_event_date(event_date_num) {

    var write_type = $('#write_type').val(); //write_type이 new가 아닌경우에 바로 db에

    var now_num = $('.event_date_wrap').children().length; // 현재 갯수 -- 이거는 처음일 때..
    var next_num = Number(now_num+1);
    var common_buttons =  '<div class="col-md-1 col-sm-6 col-6">' +
        ' <div class="btn btn-outline-action " onclick="copy_event_date('+next_num+');">+ 복사</div>' +
        '  </div>'+
        '   <div class="col-md-1 col-sm-6 col-6">' +
        ' <div class="btn btn-outline-red " onclick="delete_event_date('+next_num+');">- 삭제</div>' +
        '  </div>';
    var this_date = $('#input_event_date'+event_date_num).val();
    var this_time = $('#input_event_time'+event_date_num).val();

    var time_option  = null;
    for(var i=0; i<24; i++){
        if(this_time==i){
            time_option = time_option + '<option value="'+i+'" selected="selected">'+i+'</option>';
        }else{
            time_option = time_option + '<option value="'+i+'">'+i+'</option>';
        }
    }

    if(write_type=='modify'){
        var program_id = $('#program_id').val();

        $.ajax({
            type: "POST",
            url: '/program/add_event_date/',
            data: { program_id:program_id},//copy지만 어차피 modify 시 수정 프로세스 거치기 때문에 일단 add만 한다
            success: function (data) {
                var res = JSON.parse(data);
                switch (res){
                    case 'auth':
                        alert('본인 프로그램에만 복사할 수 있습니다.');
                        break;
                    default:
                        console.log(res);
                        var pdate_id = res;

                        var time_select ='<div class="">' +
                            '<select name="event_time['+pdate_id+']" class="custom-select form-control" id="input_event_time'+next_num+'">';

                        time_select = time_select + time_option + '</select>시 </div></div>';

                        $('.event_date_wrap').append(
                            '<div class="form-row event_date_add" id="event_date_'+next_num+'" data-event-date-id="'+pdate_id+'">' +
                            '   <div class="form-group col-md-6">' +
                            '       <input type="text" class="form-control input_basic event_date" name="event_date['+pdate_id+']" id="input_event_date'+next_num+'" placeholder="행사 날짜를 선택해주세요." value="'+this_date+'">' +
                            '    </div>' +
                            '    <div class="form-group col-md-4">' +time_select + common_buttons+
                            '</div>'

                        );

                        //캘린더 초기화
                        $("#input_event_date"+next_num).asDatepicker({
                            namespace: 'calendar',
                            lang: 'ko',
                            position: 'bottom',
                            onceClick: true,
                            date: this_date
                        });

                        break;
                }
                //
            },
            error : function (jqXHR, errorType, error) {
                console.log(errorType + ": " + error);
            }
        });

    }else{
        //time select 만 다른거..
        var time_select ='<div class="">' +
            '<select name="input_event_time[]" class="custom-select form-control" id="input_event_time'+next_num+'">';
        time_select = time_select + time_option + '</select>시 </div></div>';
        $('.event_date_wrap').append(
            '<div class="form-row event_date_add" id="event_date_'+next_num+'">' +
            '   <div class="form-group col-md-6">' +
            '       <input type="text" class="form-control input_basic event_date" name="input_event_date[]" id="input_event_date'+next_num+'" placeholder="행사 날짜를 선택해주세요." value="'+this_date+'">' +
            '    </div>' +
            '    <div class="form-group col-md-4">' +time_select +common_buttons+
            '</div>'

        );
        //캘린더 초기화
        $("#input_event_date"+next_num).asDatepicker({
            namespace: 'calendar',
            lang: 'ko',
            position: 'bottom',
            onceClick: true,
            date: this_date
        });
    }
}

function delete_event_date(event_date_num) {

    var write_type = $('#write_type').val();
    var alert_text = null;
    if(write_type=='modify') alert_text = ' 확인 버튼을 누르시면 데이터베이스에서도 바로 삭제되고 복구가 불가능합니다.';
    var answer= confirm('이 행사 일정을 삭제하시겠습니까?'+alert_text);
    if(answer){

        //번호 지정 새로하기
        //총 몇개인지 가져오기
        var now_num = $('.prod_detail_wrap').children().length;
        if(write_type=='modify'){

            var pdate_id =$('#event_date_'+event_date_num).data('eventDateId');
            var program_id = $('#program_id').val();
            //db에서 삭제
            $.ajax({
                type: "POST",
                url: '/program/delete_event_date/',
                data: { program_id:program_id, pdate_id:pdate_id },
                success: function (data) {
                    var res = JSON.parse(data);
                    console.log(res);
                    switch (res){
                        case 'auth':
                            alert('본인 프로그램의 일정만 삭제할 수 있습니다.');
                            break;
                        case 'done':
                            alert('삭제되었습니다.');
                            break;
                        default:
                            alert('에러가 발생했습니다.');
                            break;
                    }
                    //
                },
                error : function (jqXHR, errorType, error) {
                    console.log(errorType + ": " + error);
                }
            });
        }

        //product_add를 돌면서.. id, label, name 등의 이름을 바꾼다..
        $('#event_date_'+event_date_num).remove();
        $(".event_date_add").each(function(key, value) {
            //id 바꾸기
            var set_num = Number(key+1);
            $(this).attr('id','event_date_'+set_num);

            //행사 날짜 $(this).children().eq(0);
            $event_date = $(this).children().eq(0);
            $event_date.find('label').attr('for','input_event_date'+set_num);
            $event_date.find('input').attr('name','input_event_date[]');
            $event_date.find('input').attr('id','input_event_date'+set_num);

            //행사 시간 $(this).children().eq(1);
            $event_time = $(this).children().eq(1);
            $event_time.find('label').attr('for','input_event_time'+set_num);
            $event_time.find('input').attr('name','input_event_time[]');
            $event_time.find('input').attr('id','input_event_time'+set_num);

            //삭제 $(this).children().eq(4);
            $(this).children().eq(2).find('div').attr('onclick','copy_event_date('+set_num+')');
            $(this).children().eq(3).find('div').attr('onclick','delete_event_date('+set_num+')');
            console.log(key+': '+$(this).attr('id'));


        });
    }else{
        return false;
    }
}

//ticket_option


function add_qualify() {
    var write_type = $('#write_type').val(); //write_type이 new가 아닌경우에 바로 db에
    var program_id = $('#program_id').val();
    var now_num = $('#qualify_wrap').children().length; // 현재 갯수 -- 이거는 처음일 때..
    var next_num = Number(now_num+1);
    var common_buttons= '<div class="col-md-1 col-sm-6 col-6">' +
        '         <div class="btn btn-outline-danger btn-delete " onclick="delete_qualify('+next_num+');">- 삭제</div>' +
        '      </div>';

    if(write_type=='modify'){

        $.ajax({
            type: "POST",
            url: '/program/add_qualify/',
            data: { program_id:program_id},
            success: function (data) {
                var res = JSON.parse(data);
                switch (res){
                    case 'auth':
                        alert('본인 프로그램에만 추가할 수 있습니다.');
                        break;
                    default:
                        console.log(res);
                        var qualify_id = res;

                        $('#qualify_wrap').append(
                            '<div class="form-row qualify_add" id="qualify_'+next_num+'" data-qualify-option-id="'+qualify_id+'">' +
                            '   <div class="form-group col-md-11">' +
                            '       <input type="text" class="form-control input_basic" name="qualify['+qualify_id+']" id="input_qualify'+next_num+'" placeholder="내용을 입력해주세요">' +
                            '    </div>' +common_buttons +
                            '</div>'

                        );

                        break;
                }
                //
            },
            error : function (jqXHR, errorType, error) {
                console.log(errorType + ": " + error);
            }
        });

    }else{

        $('#qualify_wrap').append(
            '<div class="form-row qualify_add" id="qualify_'+next_num+'">' +
            '   <div class="form-group col-md-11">' +
            '       <input type="text" class="form-control input_basic" name="input_qualify[]" id="input_qualify'+next_num+'" placeholder="내용을 입력해주세요">' +
            '    </div>' +common_buttons +
            '</div>'

        );

    }

}

function delete_qualify(option_num) {

    var write_type = $('#write_type').val();
    var alert_text = null;
    if(write_type=='modify'){
        alert_text = ' 확인 버튼을 누르시면 데이터베이스에서도 바로 삭제되고 복구가 불가능합니다.';
    }
    var answer= confirm('이 옵션을 삭제하시겠습니까?'+alert_text);
    if(answer){
        if(write_type=='modify'){
            var qualify_id =$('#qualify_'+option_num).data('qualifyOptionId');
            var program_id = $('#program_id').val();
            if(qualify_id){//있으면 db에서 삭제

                $.ajax({
                    type: "POST",
                    url: '/program/delete_qualify/',
                    data: { qualify_id:qualify_id, program_id:program_id},
                    success: function (data) {
                        var res = JSON.parse(data);
                        switch (res){
                            case 'auth':
                                alert('본인 프로그램의 옵션만 삭제할 수 있습니다.');
                                break;

                            case 'done':
                                alert('삭제되었습니다.');
                                break;
                            default:
                                alert('에러가 발생했습니다.');
                                break;
                        }
                        //
                    },
                    error : function (jqXHR, errorType, error) {
                        console.log(errorType + ": " + error);
                    }
                });
            }


        }
        $('#qualify_'+option_num).remove();

        //번호 지정 새로하기
        //총 몇개인지 가져오기
        var now_num = $('.qualify_wrap').children().length;
        //product_add를 돌면서.. id, label, name 등의 이름을 바꾼다..
        $(".qualify_add").each(function(key, value) {
            //id 바꾸기
            var set_num = Number(key+1);
            $(this).attr('id','qualify_'+set_num);

            //상품이름 $(this).children().eq(0);
            $option_name = $(this).children().eq(0);
            $option_name.find('label').attr('for','input_qualify'+set_num);
            $option_name.find('input').attr('name','qualify[]');
            $option_name.find('input').attr('id','input_qualify'+set_num);
            //삭제 $(this).children().eq(4);
            $(this).children().eq(1).find('div').attr('onclick','delete_qualify('+set_num+')');
            console.log(key+': '+$(this).attr('id'));

        });
    }else{
        return false;
    }
}

function add_question() {
    var write_type = $('#write_type').val(); //write_type이 new가 아닌경우에 바로 db에
    var program_id = $('#program_id').val();
    var now_num = $('#question_wrap').children().length; // 현재 갯수 -- 이거는 처음일 때..
    var next_num = Number(now_num+1);

    if(write_type=='modify'){

        $.ajax({
            type: "POST",
            url: '/program/add_qna/',
            data: { program_id:program_id},
            success: function (data) {
                var res = JSON.parse(data);
                switch (res){
                    case 'auth':
                        alert('본인 프로그램에만 추가할 수 있습니다.');
                        break;
                    default:
                        console.log(res);
                        var qna_id = res;
                        $('#question_wrap').append(
                            '<div class="form-row question_add" id="question_'+next_num+'" data-qna-option-id="'+qna_id+'">' +
                            '<div class="form-group col-md-11">' +
                            '                <label for="input_question'+next_num+'">질문</label>' +
                            '                <input type="text" name="question['+qna_id+']"  class="form-control" id="input_question'+next_num+'">' +
                            '                <div class="form_guide_gray">질문 내용을 입력해주세요</div>' +
                            '            </div>' +
                            '            <div class="col-md-1">' +
                            '                <div class="btn btn-outline-danger  btn-delete" onclick="javascript:delete_question('+next_num+');">- 삭제</div>' +
                            '            </div>' +
                            '            <div class="form-group col-md-12">' +
                            '                <label for="input_question'+next_num+'">답변</label>' +
                            '                <input type="text" name="answer['+qna_id+']"  class="form-control" id="input_answer'+next_num+'">' +
                            '                <div class="form_guide_gray">질문에 대한 답변을 입력해주세요</div>' +
                            '            </div>'+
                            '</div>'
                        );
                        break;
                }
                //
            },
            error : function (jqXHR, errorType, error) {
                console.log(errorType + ": " + error);
            }
        });

    }else{
        $('#question_wrap').append(
            '<div class="form-row question_add" id="question_'+next_num+'">' +
            '<div class="form-group col-md-11">' +
            '                <label for="input_question'+next_num+'">질문</label>' +
            '                <input type="text" name="input_question[]"  class="form-control" id="input_question'+next_num+'">' +
            '                <div class="form_guide_gray">질문 내용을 입력해주세요</div>' +
            '            </div>' +
            '            <div class="col-md-1">' +
            '                <div class="btn btn-outline-danger  btn-delete" onclick="javascript:delete_question('+next_num+');">- 삭제</div>' +
            '            </div>' +
            '            <div class="form-group col-md-12">' +
            '                <label for="input_question'+next_num+'">답변</label>' +
            '                <input type="text" name="input_answer[]"  class="form-control" id="input_answer'+next_num+'">' +
            '                <div class="form_guide_gray">질문에 대한 답변을 입력해주세요</div>' +
            '            </div>'+
            '</div>'
        );
    }

}

function delete_question(option_num) {

    var write_type = $('#write_type').val();
    var alert_text = null;
    if(write_type=='modify'){
        var pqna_id =$('#question_'+option_num).data('questionOptionId');
        alert_text = ' 확인 버튼을 누르시면 데이터베이스에서도 바로 삭제되고 복구가 불가능합니다.';
        var program_id = $('#program_id').val();
    }
    var answer= confirm('이 옵션을 삭제하시겠습니까?'+alert_text);
    if(answer){

        if(write_type=='modify'){

            $.ajax({
                type: "POST",
                url: '/program/delete_qna/',
                data: { pqna_id:pqna_id, program_id:program_id},
                success: function (data) {
                    var res = JSON.parse(data);
                    switch (res){
                        case 'auth':
                            alert('본인 프로그램의 질답만 삭제할 수 있습니다.');
                            break;

                        case 'done':
                            alert('삭제되었습니다.');
                            break;
                        default:
                            alert('에러가 발생했습니다.');
                            break;
                    }
                    //
                },
                error : function (jqXHR, errorType, error) {
                    console.log(errorType + ": " + error);
                }
            });
        }

        $('#question_'+option_num).remove();

        //번호 지정 새로하기
        //총 몇개인지 가져오기
        var now_num = $('.question_wrap').children().length;
        //product_add를 돌면서.. id, label, name 등의 이름을 바꾼다..
        $(".question_add").each(function(key, value) {
            //id 바꾸기
            var set_num = Number(key+1);
            $(this).attr('id','question_'+set_num);

            //상품이름 $(this).children().eq(0);
            $question = $(this).children().eq(0);
            $question.find('label').attr('for','input_question'+set_num);
            $question.find('input').attr('name','question[]');
            $question.find('input').attr('id','input_question'+set_num);
            //삭제 $(this).children().eq(4);
            $(this).children().eq(1).find('div').attr('onclick','delete_question('+set_num+')');

            $answer = $(this).children().eq(2);
            $answer.find('label').attr('for','input_answer'+set_num);
            $answer.find('input').attr('name','answer[]');
            $answer.find('input').attr('id','input_answer'+set_num);


            console.log(key+': '+$(this).attr('id'));


        });
    }else{
        return false;
    }
}

$('.prod_ok').click(function(e){
    $('.upload_error').hide();

    var agree_pricing =$('#agree_pricing').prop('checked');
    var agree_payment =$('#agree_payment').prop('checked');

    var regExp = /^[0-9]+$/;
    var account_val = $('#input_bank_account').val();

    var open = new Date($('.open_date').val());
    var close = new Date($('.close_date').val());
    var diff = close-open;
    var day_num = 24 * 60 * 60 * 1000;// 시 * 분 * 초 * 밀리세컨

    var get_diff = parseInt(diff/day_num);

    //오늘만 등록하는건 있을 수 있으니까 제외
    if(get_diff>100){
        $('.close_date').focus();
        $('#pue_close').text('입금 기간이 '+get_diff+'일 입니다. 입금 기간이 100일 이상인 상품은 등록할 수 없습니다. 종료일을 적절히 설정해주시고, 종료일 이전에 종료 날짜를 다시 수정해주세요.').show();
        alert('입금 기간이 100일 이상인 상품은 등록할 수 없습니다. 종료일을 다시 설정해주세요.');
        return false;
    }

    if(!regExp.test(account_val)) {
        $('#input_bank_account').focus();
        alert('계좌번호에는 숫자만 입력할 수 있습니다.');

        return false;
    }

    var prod_title = $('.prod_title_input').val();
    if(prod_title=='') {
        alert('행사 이름(제목)을 입력하세요.');
        return false;
    }

    if(prod_title.match(/,/)||prod_title.match(/"/)||prod_title.match(/'/)||prod_title.match(/</)||prod_title.match(/>/)){
        alert('행사 이름(제목)에는 콤마(,), 큰따옴표, 작은따옴표, 부등호(<,>)가 들어갈 수 없습니다.');
        return false;
    }

    var district = $('#district').val();
    if(district=='') {
        $('#district').focus();
        alert('행사가 열리는 지역을 입력하세요.');
        return false;
    }

    var venue = $('#venue').val();
    if(venue=='') {
        $('#venue').focus();
        alert('행사 장소(주소)를 입력하세요.');
        return false;
    }

    var ticket_point =  check_ticket_num(e,0);
    var event_date_point = check_event_date_num(e,0);
    if(ticket_point==0 && event_date_point==0){
        var answer= confirm('티켓이 판매되기 전까지는 옵션의 추가, 삭제, 변경이 가능합니다. 티켓이 판매된 후에는 불가능합니다. 폼 주소를 공유하시기 전에 꼭 티켓 옵션을 확인해주세요.');
        if(!answer) e.preventDefault();
    }else{

        e.preventDefault();
    }

    if(agree_pricing==false || agree_payment==false){
        alert('동의 사항에 동의 해주세요.');
        e.preventDefault();
        return false;
    }



    //모든  input에서 컴마 찾기..
});

function check_ticket_num(event,point) {
    $(".ticket_add").each(function(key, value) {
        //id 바꾸기
        var regExp = /^[0-9]+$/;
        var set_num = Number(key+1);
        $(this).attr('id','ticket_'+set_num);

        //가격 $(this).children().eq(1);
        $prod_name = $(this).children().eq(0);
        //가격 $(this).children().eq(1);
        $prod_price = $(this).children().eq(1);

        //수량 $(this).children().eq(2);
        $prod_num = $(this).children().eq(2);

        //수량제한 $(this).children().eq(3);
        $prod_limit = $(this).children().eq(3);

        var name_val = $prod_name.find('input').val();
        var price_val = $prod_price.find('input').val();
        var num_val = $prod_num.find('input').val();
        var limit_val = $prod_limit.find('input').val();
        if(name_val.match(/,/)||name_val.match(/"/)||name_val.match(/'/)){
            alert('티켓 이름에는 콤마(,), 큰따옴표, 작은따옴표가 들어갈 수 없습니다.');
            point ++;
        }
        if(name_val==null || name_val==''){

            alert("티켓 이름은 비워둘 수 없습니다.");
            point ++;
            event.preventDefault();

        }
        if ( !regExp.test(price_val) ) {
            alert("가격에는 숫자만 입력할 수 있습니다.");
            point ++;
            event.preventDefault();

        }
        if ( !regExp.test(num_val) ) {
            alert("수량(재고)는 숫자만 입력할 수 있습니다.");
            point ++;
            event.preventDefault();
        }
        if ( !regExp.test(limit_val) ) {
            alert("수량 제한 칸에는 숫자만 입력할 수 있습니다.");
            point ++;
            event.preventDefault();
        }
    });

    return point;

}
function check_event_date_num(event,point) {
    var today = new Date($('#today').val());
    $(".event_date_add").each(function (key, value) {

        //id 바꾸기
        var set_num = Number(key + 1);
        var this_id = $(this).attr('id', 'event_date_' + set_num).attr('id');

        $event_date = $(this).children().eq(0);
        var event_date_val =new Date( $event_date.find('input').val());

        var diff = event_date_val-today;
        var day_num = 24 * 60 * 60 * 1000;// 시 * 분 * 초 * 밀리세컨

        var get_diff = parseInt(diff/day_num);

        if(get_diff<=0){

            alert("행사 날짜는 오늘이거나 오늘 이전일 수 없습니다.");
            point ++;
            event.preventDefault();

        }
    });

    return point;

}

function submit_program() {
    //url에 숫자, 영문만 가능하게.
    var price = $('#price').val();
    var participant = $('#participant').val();
    var regex_number = /^[0-9+]*$/;
    if (!regex_number.test(price)) {
        alert('가격에는 숫자만 입력할 수 있습니다.');
        event.preventDefault();
        return false;
    }
    if (!regex_number.test(participant)) {
        alert('참가 인원에는 숫자만 입력할 수 있습니다.');
        event.preventDefault();
        return false;
    }

    //제목에 <>, 불가능
    var program_title = $('#program_title').val();

    if(program_title.match(/,/)||program_title.match(/"/)||program_title.match(/'/)||program_title.match(/</)||program_title.match(/>/)){
        alert('프로그램 제목에는 콤마(,), 큰따옴표, 작은따옴표, 부등호(<,>)가 들어갈 수 없습니다.');
        event.preventDefault();
        return false;
    }
    $('#input_mirror').val($('.ql-editor').html());

    $('#program_form').submit();
}


$("#program_title").keyup(function(e) {

    var input_text = $(this).val();
    if(input_text.match(/,/)||input_text.match(/"/)||input_text.match(/'/)||input_text.match(/</)||input_text.match(/>/)){
        alert('팀 제목에는 콤마(,), 큰따옴표, 작은따옴표, 부등호(<,>)가 들어갈 수 없습니다.');
        return false;
        e.preventDefault();
    }
});

$("#price").keyup(function(e) {

    var price = $(this).val();
    var regex_number = /^[0-9+]*$/;
    if (!regex_number.test(price)) {
        alert('가격에는 숫자만 입력할 수 있습니다.');
        e.preventDefault();
        return false;
    }
});

$("#participant").keyup(function(e) {

    var participant = $(this).val();
    var regex_number = /^[0-9+]*$/;
    if (!regex_number.test(participant)) {
        alert('참가 인원에는 숫자만 입력할 수 있습니다.');
        e.preventDefault();
        return false;
    }
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

            //주소만 필요함
            document.getElementById('address').value = fullAddr;

        }
    }).open();
}
