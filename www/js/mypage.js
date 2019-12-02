
function phone_check() {

    var regExp = /^[0-9]+$/; // 모든 곳에서 해당..
    var phone = $('#verify_phone').val();
    var digit = phone.toString().length;
    if ( !regExp.test(phone) ||( digit < 9 || digit > 12 ) ) {
        alert("전화번호를 숫자만 입력해주세요.");
        event.preventDefault();
    }

}