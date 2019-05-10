
$('.btn-delete').on('click',function(e){
    var answer= confirm('정말 삭제하시겠습니까?');
    if(!answer) {
        e.preventDefault();
    }
});