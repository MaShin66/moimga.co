var Size = Quill.import('attributors/style/size');
Size.whitelist = ['12px','14px', '16px', '18px','20px', '22px', '24px','26px'];
Quill.register(Size, true);

var toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'size':  ['12px', '14px', '16px', '18px', '20px', '22px', '24px', '26px'] }],
    // [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
    [{ 'align': [] }],
    ['link','image'],
    // remove formatting button  ['link','image'],
    ['clean']

];
var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
        toolbar: {
            container: toolbarOptions,
            handlers: {
                image: imageHandler
            }
        },

    },
});


function imageHandler() {
    var range = this.quill.getSelection();
    var value = prompt('이미지 주소를 붙여넣으세요.');
    this.quill.insertEmbed(range.index, 'image', value, Quill.sources.USER);
}

$(document).scroll(function(){
    editor_scroll();
});

//editor toolbar 설정
function editor_scroll() {
    var toolbar_org = $('#editor').offset().top;
    var docu_top = $(document).scrollTop();
    var now_toolbar_top = toolbar_org - docu_top;
    var contents_end_top = $('.contents_end').offset().top -docu_top;

    if(now_toolbar_top<0 && contents_end_top>0){
        $('.ql-toolbar').addClass('ql-fixed');
    }else{
        $('.ql-toolbar').removeClass('ql-fixed');
    }
}

function autosave(location) {
    var unique_id = Number($('#unique_id').val());
    var contents = $('.ql-editor').html();
    $.ajax({
        type: "POST",
        url: "/"+location+"/autosave/",
        data: {
            unique_id: unique_id ,
            contents: contents},
        success: function (data) {
            var result = data.trim();
            if(result=='auth'){
                console.log('저장할 수 없습니다.'); //내 아이디가 아님
            }else{
                //자동 저장 성공
                console.log('done');
				$('.toast').show().delay(2000).fadeOut(300);
            }
        },
        error : function (jqXHR, errorType, error) {
            console.log(errorType + ": " + error);

            return 0;
        }
    });
}


function get_contents(location) {
    var unique_id = Number($('#unique_id').val());

    var answer= confirm('확인을 누르시면 최근에 저장된 자동 저장 내용을 불러옵니다.');
    if(answer) {
        $.ajax({
            type: "POST",
            url: "/"+location+"/get_contents/",
            data: {
                unique_id: unique_id},
            success: function (data) {
                var result = JSON.parse(data);
                if(result=='auth'){
                    console.log('불러올 수 없습니다.'); //내 아이디가 아님
                }else{
                    var ga_location = location;
                    if(location=='prod') ga_location = 'product';
                    ga_send_event('load', ga_location, 'draft');

                    if(result==null){
                        console.error('empty');
                        alert('저장된 내용이 없으므로 상품 설명이 변경되지 않았습니다.');
                    }else{
                        console.log('done');
                        $('.ql-editor').html(result);
                    }

                }
            },
            error : function (jqXHR, errorType, error) {
                console.log(errorType + ": " + error);

                return 0;
            }
        });
    }else{

        return false;
        //안가져옴
    }
}


