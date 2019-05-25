<?php

/*basic*/
$lang['currency']='원';
$lang['year']='년';
$lang['month']='월';
$lang['date']='일';
$lang['hour']='시';
$lang['min']='분';
$lang['bank']='은행';
$lang['account']='계좌번호';
$lang['memo']='메모';
$lang['or']='또는';
$lang['d_day']='%s일';
$lang['day_passed']='지남';

$lang['online']='통신판매';
$lang['offline']='현장수령';
$lang['basic']='기본';
$lang['shipping']='배송';

$lang['number']='번호';
$lang['view_more']='보기';

$lang['email']='이메일';
$lang['password']='비밀번호';
$lang['status']='상태';
$lang['form_status']='입금 확인';
$lang['soldout']='품절';
$lang['account_hidden']='계좌 비공개';
$lang['account_copy']='계좌번호 복사';

$lang['form_exist']='제출한 폼이 있습니다.';
$lang['go_form']='폼 확인하기';
$lang['go_modify']='수정하러 가기';
$lang['want_to_modify']='수정하시겠습니까?';
$lang['go_login']='로그인하기';
$lang['login_needed']='폼 작성을 위해 로그인이 필요합니다.';

$lang['form_ended']='폼이 종료되었습니다.';
$lang['expired']='제출 기한이 지났습니다.';
$lang['form_before']='폼 오픈 전입니다.';
$lang['form_soldout']='모든 옵션이 품절이므로 폼을 제출할 수 없습니다.';

$lang['confirm']='확인';
$lang['save']='저장';
$lang['modify']='수정';
$lang['delete']='삭제';
$lang['copy']='복사';
$lang['add']='추가';

$lang['prod_upload']='상품 등록';

/*form 작성*/
$lang['moimga_form_write'] = 'moimga 폼 작성';
$lang['moimga_demand_form_write'] = 'moimga 수요조사 폼 작성';
$lang['form_option'] = '주문 상품';
$lang['form_only_num'] = '수량을 숫자로만 입력하세요. (예시: 1)';
$lang['form_only_num_without_char'] = '특수문자를 제외한 숫자만 입력해주세요.';
$lang['only_num_placeholder'] = '숫자만 입력하세요.';
$lang['form_soldout_desc'] = '품절된 상품은 주문하실 수 없습니다. (품절 상품 포함 후 주문하시면 폼이 작성되지 않습니다.)';
$lang['form_soldout_realtime']='재고는 실시간으로 반영되므로, 현재 보이는 재고와 다를 수 있습니다. (먼저 폼을 제출한 사람이 있으면 폼이 제출되지 않을 수 있습니다.)';

$lang['form_memo_desc']='판매자가 요청한 메모가 있는 경우 꼭 적어주세요. 특이사항이 없는 경우 비워주세요.';
$lang['form_memo_mandatory']='메모가 필수입니다. 상세 설명을 참고하여 폼 제출시 메모를 적어주세요. 메모를 적지 않으면 폼이 제출되지 않습니다.';
$lang['form_memo_ph']='메모를 입력해주세요.';
$lang['after_form_done']='입금확인이 된 경우에 수량을 수정하실 수 없습니다. 수정을 원하시면 이 화면을 캡쳐하여 이 페이지의 주소와 함께 관리자에게 연락해주세요.';
$lang['after_form_done_delivery']='입금확인이 된 경우에 배송방법을 수정하실 수 없습니다. 수정을 원하시면 이 화면을 캡쳐하여 이 페이지의 주소와 함께 관리자에게 연락해주세요.';
$lang['deposit_now']='아직 입금 전이라면 지금 입금해주세요.<br>입금하실 곳: ';
$lang['send_message'] = '입력하신 전화번호로 상품 관련 문자 메시지가 전송됩니다.';

$lang['form_ea']='개';

$lang['form_deposit']='입금 정보';
$lang['form_deposit_name']='입금자명';
$lang['form_deposit_email']='이메일';
$lang['form_deposit_amout']='입금액';
$lang['form_deposit_date']='입금일';
$lang['form_deposit_date_desc']='이 칸이 제대로 보이지 않으시는 분들은 한번 클릭해보신 후 이용해주세요. <br>
그래도 보이지 않는다면 다음과 같은 형식으로 작성해주세요. 예) 2018-05-27 12:23:24<br>
입금 시각은 입력하지 않으셔도 됩니다.';
$lang['form_deposit_bank']='입금 은행';
$lang['form_deposit_bank_desc']='예) 국민, 국민은행';

$lang['form_delivery_method']='배송방법';
$lang['form_delivery_info']='배송 정보';

$lang['form_delivery_name']='수령자명';
$lang['form_delivery_phone']='연락처';
$lang['form_delivery_postal']='우편번호';
$lang['form_delivery_postal_find']='우편번호 찾기';
$lang['form_delivery_add']='주소';
$lang['form_delivery_add2']='상세주소';
$lang['form_delivery_all']='주소';
$lang['form_delivery_company']='배송 업체';
$lang['form_invoice']='송장 번호';
$lang['form_tracking']='배송 추적';

$lang['form_refund']='환불 정보 입력';
$lang['form_refund_desc']='미수령 혹은 변경시 필요한 환불 정보를 입력해주세요. 이 정보는 누구에게도 공개되지 않습니다. (판매자가 환불을 결정한 경우 절차에 따라 직접 전달됨) 빠른 환불을 위해 정확히 입력해주세요.';

$lang['form_refund_account']='환불 계좌번호';
$lang['form_refund_name']='예금주';

$lang['form_basic_shipping']='배송방법이 하나입니다. 기본 배송방법으로 배송됩니다.';
$lang['form_submit']='제출';
$lang['form_sub_info']='마이페이지 - 작성한 폼에서 입금 확인 여부를 확인할 수 있습니다.';
$lang['progress']='진행중입니다.';
/*form_view*/

$lang['view_period']='기간';
$lang['view_write_online']='통판 폼 작성';
$lang['view_write_offline']='현장수령 폼 작성';
$lang['view_write_demand']='수요조사 폼 작성';

$lang['view_prod_and_amount']='상품 및 가격';
$lang['view_detail']='상세 정보';
$lang['view_notice']='입금하실 때, 꼭! 배송비와 함께 입금해주세요.';

/*menu*/

$lang['search']='상품 검색';
$lang['product']='상품';
$lang['mypage']='마이페이지';
$lang['manage']='상품 관리';
$lang['logout']='로그아웃';
$lang['login']='로그인';

/*mypage*/

$lang['basic_user']='일반 회원';
$lang['menu_info']='내 정보';
$lang['menu_form']='작성한 폼';
$lang['menu_demand']='수요조사 폼';
$lang['menu_comment']='내 댓글';

$lang['mypage_basic_info']='기본 정보';
$lang['mypage_refund']='환불 계좌';
$lang['username']='닉네임';
$lang['username_desc']='상품에 노출되는 닉네임입니다.';
$lang['realname']='실명';
$lang['realname_desc']='입금자명과 동일하게 설정해두세요.';
$lang['basic_email']='가입 이메일';
$lang['form_realname_not_yet']='아직 실명을 입력하지 않았습니다.';
$lang['form_email_not_yet']='아직 폼 이메일을 입력하지 않았습니다.';
$lang['form_email']='폼 이메일';
$lang['view_form']='폼 보기';
$lang['view_demand_form']='수요조사 폼 보기';

$lang['form_please_write']='을 입력해주세요';
$lang['form_email_desc']='폼 작성시 사용되는 이메일입니다.<br>
이 이메일을 설정하지 않으시면 폼 작성시 가입 이메일이 사용됩니다.';
$lang['password_change']='비밀번호 변경';
$lang['adult_certi']='성인인증';
$lang['identification']='본인 인증';
$lang['certi_done']='인증 됨';
$lang['certi_not_yet']='인증 안됨';
$lang['go_adult_certi']='본인인증 하기';
$lang['adult_certi_desc']='본인인증 방식이 변경되어, 기존에 네이버로 인증을 받으신 경우에도 본인인증을 진행해야합니다.';


$lang['unregister']='탈퇴';

/*mypage-form*/

$lang['all']='총';
$lang['pending']='대기';
$lang['done']='완료';
$lang['detail_done']='입금 확인 완료';
$lang['error']='에러';
$lang['no_form']='아직 작성한 폼이 없습니다.';
$lang['no_comment']='아직 작성한 댓글이 없습니다.';
$lang['no_memo']='입력한 메모가 없습니다.';

$lang['form_write_date']='폼 작성일';
$lang['form_number']='폼 번호';
$lang['form_type']='타입';

$lang['form_buy']='구매';
$lang['form_buy_amount']='수량';

$lang['demand_form_number']='수요조사 폼 번호';
$lang['demand_form_info']='입력 정보';
$lang['privacy_delete']='개인정보 폐기';
$lang['privacy_delete_desc']='"개인정보 폐기" 버튼을 누르시면 수령인 이름, 이메일, 휴대폰 번호, 입금일, 주소, 입금액, 은행 내역이 삭제됩니다. (주문 상품 관련 정보는 유지됩니다.) 꼭 구매하신 상품을 받으신 후에 실행해주세요.';


$lang['dont_send_email']='입금 확인 \'메일\'은 전송되지 않습니다. 이 페이지(\'마이페이지 - 작성한 폼\') 에서 입금확인 상태를 확인할 수 있습니다.';
$lang['status_done']='입금 확인이 완료되었습니다. 입금 확인 메일은 전송되지 않습니다.';
$lang['ask_prod_user']='수정을 원하시면 %s님에게 직접 문의해주세요.';

$lang['form_tracking_etc']='배송 업체가 기타로 등록되어있습니다. 판매자에게 문의하여 직접 추적하세요.';

//pickup
$lang['form_pickup_status']='수령 상태';
$lang['form_pickup_pending']='수령 전';
$lang['form_pickup_done']='수령 완료';
$lang['form_pickup_date']='수령일';
$lang['form_pickup_qrcode']='이 코드를 현장수령 시 판매자에게 보여주세요.';


/*footer*/
$lang['about']='서비스 소개';
$lang['faq']='자주 묻는 질문';
$lang['term']='이용약관';
$lang['privacy']='개인정보처리방침';
$lang['refund_term']='결제 및 환불 정책';

/*auth*/

$lang['remember_me']='로그인 기억';
$lang['regi_email']='이메일로 회원가입';
$lang['login_problem']='소셜 로그인이 안되시나요?';
$lang['forgot_password']='비밀번호 찾기';
$lang['login_with_naver']='네이버 계정으로 로그인';
$lang['login_with_kakao']='카카오 계정으로 로그인';
$lang['login_with_google']='구글 계정으로 로그인';
$lang['login_please_check']='회원 가입 시 개인정보 승인 팝업에서 모든 내용 체크 후 승인 버튼을 눌러주세요.';

/*search*/

$lang['search_result']='검색 결과';
$lang['all']='전체';
$lang['product_name']='이름';
$lang['product_user']='판매자';
$lang['option']='옵션';
$lang['contents']='내용';

$lang['sort_recent']='최신 순';
$lang['sort_old']='오래된 순';
$lang['search_result_desc']='의 검색 결과';
$lang['search_try_again']='검색 결과가 없습니다.<br>다른 검색어로 검색해보세요!';

/*main*/
$lang['latest_form']='%s 님이 최근 작성한 폼';
$lang['latest_prod']='최근 판매중인 상품';
$lang['product_list']='상품 목록';

/*comment*/

$lang['comment']='댓글';
$lang['secret_comment']='비밀 댓글';
$lang['write_comment']='댓글 달기';
$lang['write_reply']='답글';
$lang['comment_disable']='댓글을 달 수 없는 상품입니다.';
?>
